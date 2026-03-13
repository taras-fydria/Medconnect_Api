<?php

namespace App\User\OpenApi;

use App\User\DTO\LoginUserDTO;
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
                        new OA\Property(
                            property: 'user',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'phone', type: 'string', example: '+79991234567'),
                                new OA\Property(property: 'roles', type: 'array', items: new OA\Items(type: 'string'), example: ['ROLE_USER']),
                            ],
                            type: 'object'
                        ),
                    ])
                ),
                new OA\Response(response: 401, description: 'Wrong credentials'),
            ]
        );
    }
}
