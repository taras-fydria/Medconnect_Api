<?php

namespace App\Doctor\OpenApi;

use App\Doctor\DTO\OutputDoctorDTO;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_METHOD)]
class ShowDoctorOperation extends OA\Get
{
    public function __construct()
    {
        parent::__construct(
            path: '/api/doctor/{doctorId}',
            summary: 'Get a doctor by ID',
            parameters: [
                new OA\Parameter(name: 'doctorId', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Doctor found',
                    content: new OA\JsonContent(ref: new Model(type: OutputDoctorDTO::class))
                ),
                new OA\Response(response: 401, description: 'Unauthorized'),
                new OA\Response(response: 404, description: 'Doctor not found'),
            ]
        );
    }
}
