<?php

namespace App\Controller\Admin;

use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class AdminController
 *
 * @package App\Controller\Admin
 */
class AdminController extends AbstractController
{
    /** @var AdminRepository */
    protected $adminRepository;

    /**
     * AdminController constructor.
     *
     * @param AdminRepository $adminRepository
     */
    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    /**
     * @param Request $request
     */
    protected function checkAdminAuthority(Request $request)
    {
        $accessToken = $request->headers->get('access-token');
        if (is_null($accessToken)) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Unauthorized');
        }

        $admin = $this->adminRepository->findOneByToken($accessToken);
        if (is_null($admin)) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Unauthorized');
        }
    }
}
