<?php

namespace App\Repository\Quinque;

use App\Entity\Quinque\Convocatoria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'quinque_convocatoria')]
class ConvocatoriaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Convocatoria::class);
    }

}
