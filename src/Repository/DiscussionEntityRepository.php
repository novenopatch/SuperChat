<?php

namespace App\Repository;

use App\Entity\DiscussionEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DiscussionEntity>
 *
 * @method DiscussionEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method DiscussionEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method DiscussionEntity[]    findAll()
 * @method DiscussionEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiscussionEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DiscussionEntity::class);
    }

//    /**
//     * @return DiscussionEntity[] Returns an array of DiscussionEntity objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DiscussionEntity
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
