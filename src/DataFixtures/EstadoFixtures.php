<?php

namespace App\DataFixtures;

use App\Entity\Peticion\Estado;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EstadoFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $estados = [
            ['nombre' => 'Nuevo', 'color' => 'primary'],
            ['nombre' => 'En curso', 'color' => 'warning'],
            ['nombre' => 'Resuelto', 'color' => 'success'],
            ['nombre' => 'Cerrado', 'color' => 'secondary']
        ];

        foreach ($estados as $estadoData) {
            $estado = new Estado();
            $estado->setNombre($estadoData['nombre']);
            $estado->setColor($estadoData['color']);
            $manager->persist($estado);
        }

        $manager->flush();
    }
}
