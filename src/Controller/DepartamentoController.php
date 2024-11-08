<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DepartamentoController extends AbstractController
{
    #[Route('/departamento', name: 'departamento')]
    public function index(): Response
    {
        return $this->render('departamento/index.html.twig', [
            'controller_name' => 'DepartamentoController',
        ]);
    }
}
