<?php

namespace App\User\OpenApi;

use App\User\DTO\LoginUserDTO;
use App\User\UserEntity;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_METHOD)]
class LoginOperation extends OA\Post
{
    public function __construct()
    {
        parent::__construct(
            path: '/api/user/login',
            summary: 'Authenticate and receive a JWT token',
            security: [],
            requestBody: new OA\RequestBody(
                required: true,
                content: new OA\JsonContent(ref: new Model(type: LoginUserDTO::class))
            ),
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'JWT token issued',
                    content: new OA\JsonContent(properties: [
                        new OA\Property(property: 'token', type: 'string', example: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...'),
                        new OA\Property(property: 'user', ref: new Model(type: UserEntity::class)),
                    ])
                ),
                new OA\Response(response: 401, description: 'Wrong credentials'),
            ]
        );
    }
}
