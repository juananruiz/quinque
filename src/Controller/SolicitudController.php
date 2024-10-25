<?php
// src/Controller/SolicitudController.php

namespace App\Controller;

use App\Entity\Solicitud;
use App\Entity\Merito;
use App\Form\MeritoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SolicitudController extends AbstractController
{
    #[Route('/solicitud/{id}', name: 'solicitud_show')]
    public function show(Solicitud $solicitud): Response
    {
        $merito = new Merito();
        $form = $this->createForm(MeritoType::class, $merito);

        return $this->render('solicitud/show.html.twig', [
            'solicitud' => $solicitud,
            'meritos' => $solicitud->getMeritos(),
            'form' => $form->createView(),
        ]);
    }
}