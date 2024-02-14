<?php

namespace App\Repository;

use App\Entity\MessageContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MessageContent>
 *
 * @method MessageContent|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessageContent|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessageContent[]    findAll()
 * @method MessageContent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessageContent::class);
    }

//    /**
//     * @return MessagesContent[] Returns an array of MessagesContent objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MessagesContent
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
