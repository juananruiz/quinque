<?php

namespace App\Repository\Peticion;

use App\Entity\Peticion\Unidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Unidad>
 *
 * @method Unidad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Unidad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Unidad[]    findAll()
 * @method Unidad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnidadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Unidad::class);
    }
}
