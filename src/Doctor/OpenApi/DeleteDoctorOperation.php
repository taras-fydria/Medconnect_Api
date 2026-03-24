<?php

namespace App\Doctor\OpenApi;

use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_METHOD)]
class DeleteDoctorOperation extends OA\Delete
{
    public function __construct()
    {
        parent::__construct(
            path: '/api/doctor/{doctorId}',
            summary: 'Delete a doctor',
            parameters: [
                new OA\Parameter(name: 'doctorId', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            ],
            responses: [
                new OA\Response(response: 204, description: 'Doctor deleted'),
                new OA\Response(response: 401, description: 'Unauthorized'),
                new OA\Response(response: 404, description: 'Doctor not found'),
            ]
        );
    }
}
