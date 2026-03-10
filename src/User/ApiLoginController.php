<?php

namespace App\User;

use App\User\DTO\RegisterUserDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

final class ApiLoginController extends AbstractController
{
    public function __construct(
        private readonly UserService $service
    )
    {
    }


    #[Route('/api/login', name: 'api_login')]
    public function index(#[CurrentUser] ?UserEntity $user): JsonResponse
    {
        if (null == $user) {
            return $this->json([
                'message' => 'missing credentials',

            ], 401);
        }

        $token = '1234';

        return $this->json([
            'user'  => $user->getUserIdentifier(),
            'token' => $token,
        ]);
    }

    #[Route(
        path: '/api/register',
        name: 'api_register',
        requirements: [
            '_format' => 'json'
        ],
        methods: ['POST']
    )]
    public function register(
        Request $request,
    ): JsonResponse
    {
        $data = $request->toArray();


        $dto = new RegisterUserDTO(
            phone: $data['phone'] ?? '',
            password: $data['password'] ?? '',
        );

        $result = $this->service->register($dto);

        return $this->json($result, status: 201);
    }
}
