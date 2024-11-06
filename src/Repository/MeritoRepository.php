<?php

namespace App\Repository;

use App\Entity\Merito;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Merito>
 */
#[ORM\Table(name: 'merito')]
class MeritoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Merito::class);
    }

//    /**
//     * @return Merito[] Returns an array of Merito objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Merito
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
