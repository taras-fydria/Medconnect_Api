<?php

namespace App\Doctor\OpenApi;

use App\Doctor\DTO\OutputDoctorDTO;
use App\Doctor\DTO\UpdateDoctorDTO;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_METHOD)]
class UpdateDoctorOperation extends OA\Put
{
    public function __construct()
    {
        parent::__construct(
            path: '/api/doctor/{doctorId}',
            summary: 'Update a doctor',
            parameters: [
                new OA\Parameter(name: 'doctorId', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            ],
            requestBody: new OA\RequestBody(
                required: true,
                content: new OA\JsonContent(ref: new Model(type: UpdateDoctorDTO::class))
            ),
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Doctor updated',
                    content: new OA\JsonContent(ref: new Model(type: OutputDoctorDTO::class))
                ),
                new OA\Response(response: 401, description: 'Unauthorized'),
                new OA\Response(response: 404, description: 'Doctor not found'),
                new OA\Response(response: 422, description: 'Validation failed'),
            ]
        );
    }
}
