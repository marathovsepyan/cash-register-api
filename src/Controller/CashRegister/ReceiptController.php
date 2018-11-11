<?php

namespace App\Controller\CashRegister;

use App\Entity\Product;
use App\Entity\Receipt;
use App\Entity\ReceiptProduct;
use App\Repository\CashRegisterRepository;
use App\Repository\ProductRepository;
use App\Repository\ReceiptProductRepository;
use App\Repository\ReceiptRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ReceiptController
 *
 * @package App\Controller
 */
class ReceiptController extends CashRegisterController
{
    /** @var EntityManager */
    private $entityManager;

    /** @var ProductRepository */
    private $productRepository;

    /** @var ReceiptRepository */
    private $recipeRepository;

    /** @var ReceiptProductRepository */
    private $recipeProductRepository;

    /**
     * ReceiptController constructor.
     *
     * @param CashRegisterRepository   $cashRegisterRepository
     * @param EntityManagerInterface   $entityManager
     * @param ProductRepository        $productRepository
     * @param ReceiptRepository        $recipeRepository
     * @param ReceiptProductRepository $recipeProductRepository
     */
    public function __construct(
        CashRegisterRepository $cashRegisterRepository,
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository,
        ReceiptRepository $recipeRepository,
        ReceiptProductRepository $recipeProductRepository
    ) {
        parent::__construct($cashRegisterRepository);

        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
        $this->recipeRepository = $recipeRepository;
        $this->recipeProductRepository = $recipeProductRepository;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createOneActon(Request $request)
    {
        try {
            $this->checkCashRegisterAuthority($request);
        } catch (HttpException $e) {
            return $this->json([], Response::HTTP_UNAUTHORIZED);
        }

        $recipe = new Receipt();

        $this->entityManager->persist($recipe);
        $this->entityManager->flush();

        $result = [
            'id' => $recipe->getId(),
        ];

        return $this->json($result, Response::HTTP_CREATED);
    }

    /**
     * @param int     $id
     * @param string  $barcode
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addProductToReceiptAction(int $id, string $barcode, Request $request): JsonResponse
    {
        try {
            $this->checkCashRegisterAuthority($request);
        } catch (HttpException $e) {
            return $this->json([], Response::HTTP_UNAUTHORIZED);
        }

        $product = $this->productRepository->findOneByBarcode($barcode);
        if (is_null($product)) {
            return $this->json(['message' => 'Product with given barcode could not be found'], Response::HTTP_NOT_FOUND);
        }

        try {
            $recipe = $this->recipeRepository->getById($id);
        } catch (EntityNotFoundException $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        if ($recipe->isFinished()) {
            return $this->json(
                ['message' => 'Receipt is finished, and products not be added to it any more'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $recipeProduct = $this->recipeProductRepository->findOneByReceiptIdAndProductId($id, $product->getId());

        if (is_null($recipeProduct)) {
            $recipeProduct = new ReceiptProduct();
            $recipeProduct
                ->setProduct($product)
                ->setReceipt($recipe)
                ->setAmount(1);
        } else {
            $recipeProduct
                ->setAmount($recipeProduct->getAmount() + 1)
                ->setUpdatedAt(new \DateTime('now'));
        }

        $this->entityManager->persist($recipeProduct);
        $this->entityManager->flush();

        $result = [
            'receipt_id' => $recipe->getId(),
            'added_product' => [
                'barcode' => $recipeProduct->getProduct()->getBarcode(),
                'name' => $recipeProduct->getProduct()->getName(),
                'amount' => $recipeProduct->getAmount(),
            ],
        ];

        return $this->json($result, Response::HTTP_OK);
    }

    /**
     * @param int     $id
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function changeLastProductAmountAction(int $id, Request $request): JsonResponse
    {
        try {
            $this->checkCashRegisterAuthority($request);
        } catch (HttpException $e) {
            return $this->json([], Response::HTTP_UNAUTHORIZED);
        }

        $newAmount = $request->request->get('new_amount');
        if (is_null($newAmount)) {
            return $this->json(['message' => 'Invalid request params'], Response::HTTP_BAD_REQUEST);
        }

        $newAmount = intval($newAmount);
        if ($newAmount < 1) {
            return $this->json(['message' => 'Invalid request params'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $recipe = $this->recipeRepository->getById($id);
        } catch (EntityNotFoundException $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        if ($recipe->isFinished()) {
            return $this->json(
                ['message' => 'Receipt is finalized and can not be modified any more'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $recipeLastProduct = $this->recipeProductRepository->getRecipeLastProduct($recipe->getId());
        if (is_null($recipeLastProduct)) {
            return $this->json(['message' => 'Receipt has no products'], Response::HTTP_BAD_REQUEST);
        }

        $recipeLastProduct
            ->setAmount($newAmount)
            ->setUpdatedAt(new \DateTime('now'));

        $this->entityManager->persist($recipeLastProduct);
        $this->entityManager->flush();

        $result = [
            'receipt_id' => $recipe->getId(),
            'last_product' => [
                'barcode' => $recipeLastProduct->getProduct()->getBarcode(),
                'name' => $recipeLastProduct->getProduct()->getName(),
                'amount' => $recipeLastProduct->getAmount(),
            ],
        ];

        return $this->json($result, Response::HTTP_OK);
    }

    /**
     * @param int     $id
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function finishOneAction(int $id, Request $request): JsonResponse
    {
        try {
            $this->checkCashRegisterAuthority($request);
        } catch (HttpException $e) {
            return $this->json([], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $recipe = $this->recipeRepository->getById($id);
        } catch (EntityNotFoundException $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        if ($recipe->isFinished()) {
            return $this->json([], Response::HTTP_NO_CONTENT);
        }

        $recipeProductsCount = $this->recipeProductRepository->getRecipeProductsCount($recipe->getId());
        if (0 === $recipeProductsCount) {
            return $this->json(['message' => 'Can not finish empty receipt'], Response::HTTP_BAD_REQUEST);
        }

        $recipe->setFinished(true);

        $this->entityManager->persist($recipe);
        $this->entityManager->flush();

        return $this->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @param int     $id
     * @param Request $request
     *
     * @return JsonResponse
     * @throws EntityNotFoundException
     */
    public function getOneAction(int $id, Request $request)
    {
        try {
            $this->checkCashRegisterAuthority($request);
        } catch (HttpException $e) {
            return $this->json([], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $recipe = $this->recipeRepository->getById($id);
        } catch (EntityNotFoundException $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        $recipeProductsData = $this->recipeProductRepository->getRecipeProductsData($recipe->getId());
        $result = $this->prepareRecipeData($recipe, $recipeProductsData);

        return $this->json($result, Response::HTTP_OK);
    }

    /**
     * @param Receipt $recipe
     * @param         $recipeProductsData
     *
     * @return array
     * @throws EntityNotFoundException
     */
    private function prepareRecipeData(Receipt $recipe, $recipeProductsData)
    {
        $result = [
            'recipe_id' => $recipe->getId(),
            'products' => [],
            'total_cost' => 0,
            'total_vat6' => 0,
            'total_vat21' => 0,
        ];

        $totalCost = 0;
        $totalVat6 = 0;
        $totalVat21 = 0;

        foreach ($recipeProductsData as $recipeProductsDatum) {
            $productData = [];
            $product = $this->productRepository->getById($recipeProductsDatum['productId']);

            $productData['name'] = $product->getName();
            $productData['amount'] = $recipeProductsDatum['amount'];

            if (Product::VAT_6_PERCENT === $product->getVatClass()) {
                $vatPart = $product->getCost() * 6 / 100;
                $costPerItem = $product->getCost() + $vatPart;
            } else {
                $vatPart = $product->getCost() * 21 / 100;
                $costPerItem = $product->getCost() + $vatPart;
            }

            $productTotalCost = $costPerItem * $productData['amount'];

            $productData['cost'] = [
                'per_item' => $costPerItem . '$',
                'total' => $productTotalCost . '$',
            ];

            $totalCost += $productTotalCost;

            $productData['vat'] = (Product::VAT_6_PERCENT === $product->getVatClass() ? 6 : 21) . '%';
            if (Product::VAT_6_PERCENT === $product->getVatClass()) {
                $totalVat6 += $vatPart * $productData['amount'];
            } else {
                $totalVat21 += $vatPart * $productData['amount'];
            }

            $result['products'][] = $productData;
        }

        $result['total_cost'] = $totalCost . '$';
        $result['total_vat6'] = $totalVat6 . '$';
        $result['total_vat21'] = $totalVat21 . '$';

        return $result;
    }
}
