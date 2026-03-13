<?php

namespace App\Shared\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ErrorResponse')]
class ErrorResponse
{
    #[OA\Property(type: 'string', example: 'User not found')]
    public string $error;
}
