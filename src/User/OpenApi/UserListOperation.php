<?php

namespace App\User\OpenApi;

use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_METHOD)]
class UserListOperation extends OA\Get
{
    public function __construct()
    {
        parent::__construct(
            path: '/api/user',
            summary: 'List all users',
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Array of users',
                    content: new OA\JsonContent(
                        type: 'array',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'phone', type: 'string', example: '+79991234567'),
                                new OA\Property(property: 'roles', type: 'array', items: new OA\Items(type: 'string'), example: ['ROLE_USER']),
                            ]
                        )
                    )
                ),
            ]
        );
    }
}
