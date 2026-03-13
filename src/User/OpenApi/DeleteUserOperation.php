<?php

namespace App\User\OpenApi;

use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_METHOD)]
class DeleteUserOperation extends OA\Delete
{
    public function __construct()
    {
        parent::__construct(
            path: '/api/user/{id}',
            summary: 'Delete a user',
            parameters: [
                new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
            ],
            responses: [
                new OA\Response(response: 204, description: 'User deleted'),
                new OA\Response(response: 404, description: 'User not found'),
            ]
        );
    }
}
