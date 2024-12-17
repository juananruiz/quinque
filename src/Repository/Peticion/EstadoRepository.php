<?php

namespace App\Repository\Peticion;

use App\Entity\Peticion\Estado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Estado>
 *
 * @method Estado|null find($id, $lockMode = null, $lockVersion = null)
 * @method Estado|null findOneBy(array $criteria, array $orderBy = null)
 * @method Estado[]    findAll()
 * @method Estado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Estado::class);
    }
}
