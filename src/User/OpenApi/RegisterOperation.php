<?php

namespace App\User\OpenApi;

use App\User\DTO\RegisterUserDTO;
use App\User\UserEntity;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_METHOD)]
class RegisterOperation extends OA\Post
{
    public function __construct()
    {
        parent::__construct(
            path: '/api/user/register',
            summary: 'Register a new user',
            security: [],
            requestBody: new OA\RequestBody(
                required: true,
                content: new OA\JsonContent(ref: new Model(type: RegisterUserDTO::class))
            ),
            responses: [
                new OA\Response(
                    response: 201,
                    description: 'User created',
                    content: new OA\JsonContent(ref: new Model(type: UserEntity::class))
                ),
                new OA\Response(response: 409, description: 'Phone number already registered'),
                new OA\Response(response: 422, description: 'Validation failed'),
            ]
        );
    }
}
