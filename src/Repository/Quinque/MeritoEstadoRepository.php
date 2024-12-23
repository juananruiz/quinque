<?php

namespace App\Repository\Quinque;

use App\Entity\Quinque\Merito;
use App\Entity\Quinque\MeritoEstado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Mapping as ORM;

/**
 * @extends ServiceEntityRepository<MeritoEstado>
 *
 * @method MeritoEstado|null find($id, $lockMode = null, $lockVersion = null)
 * @method MeritoEstado|null findOneBy(array $criteria, array $orderBy = null)
 * @method MeritoEstado[]    findAll()
 * @method MeritoEstado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
#[ORM\Table(name: 'quinque_merito_estado')]

 class MeritoEstadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MeritoEstado::class);
    }

    public function save(MeritoEstado $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MeritoEstado $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
