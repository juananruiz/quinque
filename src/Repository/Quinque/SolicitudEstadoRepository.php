<?php

namespace App\Repository\Quinque;

use App\Entity\Peticion\Solicitud;
use App\Entity\Quinque\SolicitudEstado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SolicitudEstado>
 *
 * @method SolicitudEstado|null find($id, $lockMode = null, $lockVersion = null)
 * @method SolicitudEstado|null findOneBy(array $criteria, array $orderBy = null)
 * @method SolicitudEstado[]    findAll()
 * @method SolicitudEstado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
#[ORM\Table(name: 'quinque_solicitud_estado')]
class SolicitudEstadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SolicitudEstado::class);
    }

    public function save(SolicitudEstado $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SolicitudEstado $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
