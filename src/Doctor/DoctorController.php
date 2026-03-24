<?php

namespace App\Doctor;

use App\Doctor\DTO\CreateDoctorDTO;
use App\Doctor\DTO\QueryDoctorsDTO;
use App\Doctor\DTO\UpdateDoctorDTO;
use App\Doctor\Interfaces\IDoctorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/doctor', name: 'api_doctor')]
class DoctorController extends AbstractController
{
    public function __construct(
        private IDoctorService $doctorService,
    ) {}

    #[Route(path: '', name: 'api_doctor_all', methods: ['GET'])]
    public function index(Request $_): JsonResponse
    {
        $queryDTO = new QueryDoctorsDTO();
        $result   = $this->doctorService->getAllDoctors($queryDTO);
        return $this->json($result->items, headers: ['X-Total-Count' => $result->total]);
    }

    #[Route(path: '/{doctorId}', name: 'api_doctor_show', methods: ['GET'])]
    public function show(int $doctorId): JsonResponse
    {
        return $this->json($this->doctorService->getById($doctorId));
    }

    #[Route(path: '', name: 'api_doctor_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $content = $request->toArray();
        $dto     = new CreateDoctorDTO(
            firstName: $content['firstName'] ?? '',
            lastName: $content['lastName'] ?? '',
            licenseNumber: $content['licenseNumber'] ?? '',
            specialization: $content['specialization'] ?? '',
            userId: $content['userID'] ?? 0,
        );
        $result  = $this->doctorService->createNew($dto);
        return $this->json($result, status: Response::HTTP_CREATED);
    }

    #[Route(path: '/{doctorId}', name: 'api_doctor_update', methods: ['PUT'])]
    public function update(int $doctorId, Request $request): JsonResponse
    {
        $content = $request->toArray();
        $dto     = new UpdateDoctorDTO(
            id: $doctorId,
            firstName: $content['firstName'] ?? '',
            lastName: $content['lastName'] ?? '',
            specialization: $content['specialization'] ?? '',
            licenseNumber: $content['licenseNumber'] ?? '',
            userID: $content['userID'] ?? 0,
        );

        $result = $this->doctorService->update($dto);

        return $this->json($result, status: Response::HTTP_OK);
    }

    #[Route(path: '/{doctorID}', methods: ['DELETE'])]
    public function delete(int $doctorID): Response
    {
        $this->doctorService->delete($doctorID);
        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
