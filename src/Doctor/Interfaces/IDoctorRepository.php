<?php

namespace App\Doctor\Interfaces;

use App\Doctor\DTO\QueryDoctorsDTO;

interface IDoctorRepository
{
    public function get(int $id);

    public function getAll(QueryDoctorsDTO $queryDTO);

    public function create(QueryDoctorsDTO $queryDTO);

    public function update(QueryDoctorsDTO $queryDTO);

    public function delete(int $id);
}
