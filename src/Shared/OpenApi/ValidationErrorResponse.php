<?php

namespace App\Shared\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ValidationErrorResponse')]
class ValidationErrorResponse
{
    #[OA\Property(
        type: 'object',
        additionalProperties: new OA\AdditionalProperties(
            type: 'array',
            items: new OA\Items(type: 'string')
        ),
        example: ['phone' => ['Invalid phone number format']]
    )]
    public array $errors;
}
