<?php

namespace App\Doctor;

use App\Doctor\DTO\QueryDoctorsDTO;
use App\Doctor\Entity\Doctor;
use App\Doctor\Interfaces\IDoctorRepository;
use App\Shared\DTO\PaginatedResultDTO;
use App\Shared\DTO\PaginationDTO;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class DoctorRepository extends ServiceEntityRepository implements IDoctorRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Doctor::class);
    }

    private function getSearchParamsQuery(QueryBuilder $queryBuilder, QueryDoctorsDTO $dto): void
    {
    }

    protected function applyPagination(QueryBuilder $qb, PaginationDTO $pagination): void
    {
        if ($pagination->limit !== null) {
            $qb->setMaxResults($pagination->limit);
        }

        if ($pagination->offset !== null) {
            $qb->setFirstResult($pagination->offset);
        }
    }


    public function findByFilter($dto): Query
    {
        $queryBuilder = $this->createQueryBuilder('doctors');

        if (isset($dto->pagination) && $dto->pagination instanceof PaginationDTO) {
            $this->applyPagination($queryBuilder, $dto->pagination);
        }

        match (true) {
            $dto instanceof QueryDoctorsDTO => $this->getSearchParamsQuery($queryBuilder, $dto),
            default => $queryBuilder
        };

        return $queryBuilder->getQuery();
    }


    public function getAll(QueryDoctorsDTO $queryDTO = new QueryDoctorsDTO()): array
    {
        $result = $this->findByFilter($queryDTO)->getResult();

    }

    public function get(int $id): ?Doctor
    {
        return $this->find($id);
    }


    public function create(QueryDoctorsDTO $queryDTO)
    {
        throw new \LogicException('method not implemented');
    }

    public function update(QueryDoctorsDTO $queryDTO)
    {
        throw new \LogicException('method not implemented');
    }

    public function delete(int $id)
    {
        throw new \LogicException('method not implemented');
    }

    public function getFirstDoctorId(): int
    {
        return $this->findOneBy([], ['id' => 'ASC'])->id;
    }

    public function saveOne(Doctor $doctor, bool $flush = true): void
    {
        throw new \LogicException('method not implemented');
    }

    public function deleteOne(Doctor $doctor, bool $flush = true): void
    {
        throw new \LogicException('method not implemented');
    }

    public function findByUserId(int $userId): ?Doctor
    {
        throw new \LogicException('method not implemented');
    }
}
