<?php

namespace App\User\OpenApi;

use App\User\DTO\RegisterUserDTO;
use App\User\UserEntity;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_METHOD)]
class UpdateUserOperation extends OA\Put
{
    public function __construct()
    {
        parent::__construct(
            path: '/api/user/{id}',
            summary: 'Update a user',
            parameters: [
                new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
            ],
            requestBody: new OA\RequestBody(
                required: true,
                content: new OA\JsonContent(ref: new Model(type: RegisterUserDTO::class))
            ),
            responses: [
                new OA\Response(
                    response: 201,
                    description: 'User updated',
                    content: new OA\JsonContent(ref: new Model(type: UserEntity::class))
                ),
                new OA\Response(response: 404, description: 'User not found'),
                new OA\Response(response: 409, description: 'Phone already taken'),
                new OA\Response(response: 422, description: 'Validation failed'),
            ]
        );
    }
}
