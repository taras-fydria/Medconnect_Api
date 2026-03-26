<?php

namespace App\Doctor;

use App\Doctor\DTO\QueryDoctorsDTO;
use App\Doctor\Entity\Doctor;
use App\Doctor\Interfaces\IDoctorRepository;
use App\Shared\DTO\PaginationDTO;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class DoctorRepository extends ServiceEntityRepository implements IDoctorRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Doctor::class);
        $this->entityManager = $this->getEntityManager();
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


    protected function setupQueryBuilder($dto): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('doctors');

        if (isset($dto->pagination) && $dto->pagination instanceof PaginationDTO) {
            $this->applyPagination($queryBuilder, $dto->pagination);
        }

        match (true) {
            $dto instanceof QueryDoctorsDTO => $this->getSearchParamsQuery($queryBuilder, $dto),
            default => $queryBuilder
        };

        return $queryBuilder;
    }

    public function findByFilter($dto): Query
    {
        $queryBuilder = $this->setupQueryBuilder($dto);

        return $queryBuilder->getQuery();
    }


    public function getAll(QueryDoctorsDTO $queryDTO = new QueryDoctorsDTO()): array
    {
        return $this->findByFilter($queryDTO)->getResult();
    }

    public function findByDoctorID(int $id): ?Doctor
    {
        return $this->find($id);
    }

    public function getFirstID(): int
    {
        return $this->findOneBy([], ['id' => 'ASC'])->id;
    }

    public function saveOne($doctor, $flush = true): Doctor
    {
        $this->entityManager->persist($doctor);

        if ($flush) {
            $this->entityManager->flush();
        }

        return $doctor;
    }

    public function deleteOne(Doctor $doctor, bool $flush = true): void
    {
        $this->entityManager->remove($doctor);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    public function findOneByUserID(int $userId): ?Doctor
    {
        return $this->createQueryBuilder('d')
            ->where('d.user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getTotalCount(QueryDoctorsDTO $queryDTO): int
    {
        $queryBuilder = $this->createQueryBuilder('doctors')
            ->select('COUNT(doctors.id)');

        return (int)$queryBuilder->getQuery()->getSingleScalarResult();
    }

    public function findConflicts(int $userId, string $licenseNumber): array
    {
        return $this->createQueryBuilder('d')
            ->where('d.user = :userId OR d.licenseNumber = :licenseNumber')
            ->setParameter('userId', $userId)
            ->setParameter('licenseNumber', $licenseNumber)
            ->getQuery()
            ->getResult();
    }

    public function existsByLicenseNumberExcluding(string $licenseNumber, int $excludeId): bool
    {
        return (bool)$this->createQueryBuilder('d')
            ->select('COUNT(d.id)')
            ->where('d.licenseNumber = :licenseNumber AND d.id != :id')
            ->setParameter('licenseNumber', $licenseNumber)
            ->setParameter('id', $excludeId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
