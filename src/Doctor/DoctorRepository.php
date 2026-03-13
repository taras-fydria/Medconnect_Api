<?php

namespace App\Doctor;

use App\Doctor\DTO\QueryDoctorsDTO;
use App\Doctor\Interfaces\IDoctorRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class DoctorRepository extends ServiceEntityRepository implements Interfaces\IDoctorRepository
{

    public function get(int $id)
    {
        // TODO: Implement get() method.
    }

    public function getAll(QueryDoctorsDTO $queryDTO)
    {
        // TODO: Implement getAll() method.
    }

    public function create(QueryDoctorsDTO $queryDTO)
    {
        // TODO: Implement create() method.
    }

    public function update(QueryDoctorsDTO $queryDTO)
    {
        // TODO: Implement update() method.
    }

    public function delete(int $id)
    {
        // TODO: Implement delete() method.
    }
}
