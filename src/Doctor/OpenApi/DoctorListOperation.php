<?php

namespace App\Doctor\OpenApi;

use App\Doctor\DTO\OutputDoctorDTO;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_METHOD)]
class DoctorListOperation extends OA\Get
{
    public function __construct()
    {
        parent::__construct(
            path: '/api/doctor',
            summary: 'List all doctors',
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Array of doctors',
                    headers: [
                        new OA\Header(
                            header: 'X-Total-Count',
                            description: 'Total number of doctors',
                            schema: new OA\Schema(type: 'integer')
                        ),
                    ],
                    content: new OA\JsonContent(
                        type: 'array',
                        items: new OA\Items(ref: new Model(type: OutputDoctorDTO::class))
                    )
                ),
                new OA\Response(response: 401, description: 'Unauthorized'),
            ]
        );
    }
}
