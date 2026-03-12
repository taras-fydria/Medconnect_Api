<?php

namespace App\User;

use App\User\DTO\RegisterUserDTO;
use App\User\Exception\UserWrongCredentialsException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api/user')]
final class UserController extends AbstractController
{
    public function __construct(
        private readonly UserService $service
    )
    {
    }


    #[Route('/login', name: 'api_login', methods: ['POST'])]
    public function login(): never
    {
        throw new \LogicException('Handled by the firewall — this method should never be reached.');
    }

    #[Route("", name: 'api_user', methods: ['GET']), ]
    public function index(): JsonResponse
    {
        $data = $this->service->getAll();

        return new JsonResponse($data);
    }

    #[Route(
        path: '/register',
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

        return $this->json($result, status: Response::HTTP_CREATED);
    }

    #[Route(
        path: '/{id}',
        name: 'api_user_update',
        methods: ['PUT'],
    )]
    public function update(int $id, Request $request): JsonResponse
    {
        $data = $request->toArray();
        $dto  = new RegisterUserDTO(
            $data['phone'] ?? '',
            $data['password'] ?? '',
        );

        $user = $this->service->update($id, $dto);
        return $this->json($user, status: Response::HTTP_CREATED);
    }


    #[Route(path: '/{id}')]
    public function delete(int $id): JsonResponse
    {
        $this->service->delete($id);
        return $this->json(null, status: Response::HTTP_NO_CONTENT);
    }
}
