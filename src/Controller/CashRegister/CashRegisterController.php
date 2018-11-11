<?php

namespace App\Controller\CashRegister;

use App\Repository\CashRegisterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class CashRegisterController
 *
 * @package App\Controller\CashRegister
 */
class CashRegisterController extends AbstractController
{
    /** @var CashRegisterRepository */
    protected $cashRegisterRepository;

    /**
     * CashRegisterController constructor.
     *
     * @param CashRegisterRepository $cashRegisterRepository
     */
    public function __construct(CashRegisterRepository $cashRegisterRepository)
    {
        $this->cashRegisterRepository = $cashRegisterRepository;
    }

    /**
     * @param Request $request
     */
    protected function checkCashRegisterAuthority(Request $request)
    {
        $accessToken = $request->headers->get('access-token');
        if (is_null($accessToken)) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Unauthorized');
        }

        $admin = $this->cashRegisterRepository->findOneByToken($accessToken);
        if (is_null($admin)) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Unauthorized');
        }
    }
}
