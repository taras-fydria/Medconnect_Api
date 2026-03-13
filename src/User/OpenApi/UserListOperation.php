<?php

namespace App\User\OpenApi;

use App\User\UserEntity;
use Nelmio\ApiDocBundle\Attribute\Model;
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
                        items: new OA\Items(ref: new Model(type: UserEntity::class))
                    )
                ),
            ]
        );
    }
}
