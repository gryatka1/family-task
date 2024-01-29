<?php

namespace App\Repository;

use App\Entity\TaskGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TaskGroup>
 *
 * @method TaskGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaskGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method TaskGroup[]    findAll()
 * @method TaskGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaskGroup::class);
    }

    /**
     * @return array<TaskGroup>
     */
    public function findActiveTaskGroups(): array
    {
        $qb = $this->createQueryBuilder('tg');

        return $qb
            ->andWhere($qb->expr()->isNull('tg.deleted_at'))
            ->getQuery()
            ->getResult();
    }
}
