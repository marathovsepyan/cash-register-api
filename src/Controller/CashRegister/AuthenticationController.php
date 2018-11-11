<?php

namespace App\Controller\CashRegister;

use App\Repository\CashRegisterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthenticationController
 *
 * @package App\Controller\CashRegister
 */
class AuthenticationController extends AbstractController
{
    /** @var CashRegisterRepository */
    private $cashRegisterRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * AuthenticationController constructor.
     *
     * @param CashRegisterRepository $cashRegisterRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(CashRegisterRepository $cashRegisterRepository, EntityManagerInterface $entityManager)
    {
        $this->cashRegisterRepository = $cashRegisterRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function loginAction(Request $request): JsonResponse
    {
        $uid = $request->request->get('uid');
        $pwd = $request->request->get('pwd');

        if (is_null($uid) || is_null($pwd)) {
            return $this->json(['message' => 'Invalid request params'], Response::HTTP_BAD_REQUEST);
        }

        $cashRegister = $this->cashRegisterRepository->findOneByUid($uid);
        if (is_null($cashRegister)) {
            return $this->json(['message' => 'Cash register not found'], Response::HTTP_NOT_FOUND);
        }

        if (false === password_verify($pwd, $cashRegister->getPwd())) {
            return $this->json(['message' => 'Failed to authenticate'], Response::HTTP_UNAUTHORIZED);
        }

        // Generate token and set it to cash register table
        $token = hash('sha512', random_bytes(20));

        $cashRegister->setToken($token);
        $this->entityManager->persist($cashRegister);
        $this->entityManager->flush();

        return $this->json([], Response::HTTP_NO_CONTENT, ['access-token' => $cashRegister->getToken()]);
    }
}
