<?php

namespace App\Doctor;

use App\Doctor\DTO\QueryDoctorsDTO;
use App\Doctor\Interfaces\IDoctorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: 'api/doctor', name: 'api_doctor')]
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
        throw new \LogicException('Not implemented');
    }

    #[Route(path: '{doctorId}', name: 'api_doctor_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        throw new \LogicException('Not implemented');
    }


}
