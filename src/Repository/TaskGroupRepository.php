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

//    /**
//     * @return TaskGroup[] Returns an array of TaskGroup objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TaskGroup
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
