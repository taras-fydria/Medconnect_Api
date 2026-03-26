<?php

namespace App\Doctor;

use App\Doctor\DTO\CreateDoctorDTO;
use App\Doctor\DTO\QueryDoctorsDTO;
use App\Doctor\DTO\UpdateDoctorDTO;
use App\Doctor\Interfaces\IDoctorService;
use App\Doctor\OpenApi\CreateDoctorOperation;
use App\Doctor\OpenApi\DeleteDoctorOperation;
use App\Doctor\OpenApi\DoctorListOperation;
use App\Doctor\OpenApi\ShowDoctorOperation;
use App\Doctor\OpenApi\UpdateDoctorOperation;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag(name: 'Doctor')]
#[Route(path: '/api/doctor', name: 'api_doctor')]
class DoctorController extends AbstractController
{
    public function __construct(
        private readonly IDoctorService $doctorService,
    )
    {
    }

    #[DoctorListOperation]
    #[Route(path: '', name: 'api_doctor_all', methods: ['GET'])]
    public function index(Request $_): JsonResponse
    {
        $queryDTO = new QueryDoctorsDTO();
        $result   = $this->doctorService->getAllDoctors($queryDTO);
        return $this->json($result->items, headers: ['X-Total-Count' => $result->total]);
    }

    #[ShowDoctorOperation]
    #[Route(path: '/{doctorId}', name: 'api_doctor_show', methods: ['GET'])]
    public function show(int $doctorId): JsonResponse
    {
        return $this->json($this->doctorService->getById($doctorId));
    }

    #[CreateDoctorOperation]
    #[Route(path: '', name: 'api_doctor_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $dto    = CreateDoctorDTO::fromArray($request->toArray());
        $result = $this->doctorService->createNew($dto);
        return $this->json($result, status: Response::HTTP_CREATED);
    }

    #[UpdateDoctorOperation]
    #[Route(path: '/{doctorId}', name: 'api_doctor_update', methods: ['PUT'])]
    public function update(int $doctorId, Request $request): JsonResponse
    {
        $dto    = UpdateDoctorDTO::fromArray(['id' => $doctorId, ...$request->toArray()]);
        $result = $this->doctorService->update($dto);

        return $this->json($result, status: Response::HTTP_OK);
    }

    #[DeleteDoctorOperation]
    #[Route(path: '/{doctorID}', methods: ['DELETE'])]
    public function delete(int $doctorID): Response
    {
        $this->doctorService->delete($doctorID);
        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
