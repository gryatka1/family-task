<?php

namespace App\Repository;

use App\Entity\TaskGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * @return array<string>
     */
    public function findTaskGroupsTitlesByUser(UserInterface $user): array
    {
        $qb = $this->createQueryBuilder('tg');

        return $qb
            ->select('tg.title')
            ->where($qb->expr()->isNull('tg.deletedAt'))
            ->andWhere($qb->expr()->eq('tg.createdByUserId', ':userId'))
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getSingleColumnResult();
    }

    /**
     * @return array<TaskGroup>
     */
    public function findTaskGroupsByUser(UserInterface $user): array
    {
        $qb = $this->createQueryBuilder('tg');

        return $qb
            ->where($qb->expr()->isNull('tg.deletedAt'))
            ->andWhere($qb->expr()->eq('tg.createdByUserId', ':userId'))
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getResult();
    }
}
