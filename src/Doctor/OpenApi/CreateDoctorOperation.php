<?php

namespace App\Doctor\OpenApi;

use App\Doctor\DTO\CreateDoctorDTO;
use App\Doctor\DTO\OutputDoctorDTO;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_METHOD)]
class CreateDoctorOperation extends OA\Post
{
    public function __construct()
    {
        parent::__construct(
            path: '/api/doctor',
            summary: 'Create a new doctor',
            requestBody: new OA\RequestBody(
                required: true,
                content: new OA\JsonContent(ref: new Model(type: CreateDoctorDTO::class))
            ),
            responses: [
                new OA\Response(
                    response: 201,
                    description: 'Doctor created',
                    content: new OA\JsonContent(ref: new Model(type: OutputDoctorDTO::class))
                ),
                new OA\Response(response: 401, description: 'Unauthorized'),
                new OA\Response(response: 422, description: 'Validation failed or user already has a doctor profile'),
            ]
        );
    }
}
