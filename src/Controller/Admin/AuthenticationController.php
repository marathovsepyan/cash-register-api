<?php

namespace App\Controller\Admin;

use App\Repository\AdminRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthenticationController
 *
 * @package App\Controller\Admin
 */
class AuthenticationController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var AdminRepository */
    private $adminRepository;

    /**
     * AuthenticationController constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param AdminRepository        $adminRepository
     */
    public function __construct(EntityManagerInterface $entityManager, AdminRepository $adminRepository)
    {
        $this->entityManager = $entityManager;
        $this->adminRepository = $adminRepository;
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function loginAction(Request $request): JsonResponse
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        if (is_null($email) || is_null($password)) {
            return $this->json(['message' => 'Invalid request params'], Response::HTTP_BAD_REQUEST);
        }

        $admin = $this->adminRepository->findOneByEmail($email);
        if (is_null($admin)) {
            return $this->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        if (false === password_verify($password, $admin->getPassword())) {
            return $this->json(['message' => 'Failed to authenticate'], Response::HTTP_UNAUTHORIZED);
        }

        // Generate user token and set it to admin
        $randomStr = $this->generateRandomString(20);
        $token = hash('sha512', $randomStr);

        $admin->setToken($token);
        $this->entityManager->persist($admin);
        $this->entityManager->flush();

        return $this->json([], Response::HTTP_NO_CONTENT, ['access-token' => $admin->getToken()]);
    }

    /**
     * @param int $length
     *
     * @return string
     */
    private function generateRandomString(int $length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
