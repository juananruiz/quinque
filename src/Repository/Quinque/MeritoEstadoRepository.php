<?php

namespace App\Repository\Quinque;

use App\Entity\Quinque\MeritoEstado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MeritoEstado>
 *
 * @method MeritoEstado|null find($id, $lockMode = null, $lockVersion = null)
 * @method MeritoEstado|null findOneBy(array $criteria, array $orderBy = null)
 * @method MeritoEstado[]    findAll()
 * @method MeritoEstado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeritoEstadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MeritoEstado::class);
    }
}
