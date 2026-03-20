<?php

namespace App\Doctor;

use App\Doctor\DTO\QueryDoctorsDTO;
use App\Doctor\Interfaces\IDoctorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route(path: '/api/doctor', name: 'api_doctor')]
class DoctorController extends AbstractController
{
    public function __construct(
        private IDoctorService $doctorService,
    )
    {
    }

    #[Route(path: '', name: 'api_doctor_all', methods: ['GET'])]
    public function index(QueryDoctorsDTO $queryDTO): JsonResponse
    {
        $result = $this->doctorService->getAllDoctors($queryDTO);
        return $this->json($result->items, headers: ['X-Total-Count' => $result->total]);
    }

    #[Route(path: '/{doctorId}', name: 'api_doctor_show', methods: ['GET'])]
    public function show(int $doctorId): JsonResponse
    {
        return $this->json($this->doctorService->getById($doctorId));
    }

    #[Route(path: '/{doctorId}', name: 'api_doctor_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        throw new \LogicException('Not implemented');
    }
}
