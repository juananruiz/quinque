<?php
// src/Controller/QuinquenioSolicitudController.php

namespace App\Controller;

use App\Entity\QuinquenioSolicitud;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuinquenioSolicitudController extends AbstractController
{
    #[Route('/quinquenio_solicitud/{id}', name: 'quinquenio_solicitud_show')]
    public function show(QuinquenioSolicitud $quinquenioSolicitud): Response
    {
        return $this->render('quinquenio_solicitud/show.html.twig', [
            'quinquenioSolicitud' => $quinquenioSolicitud,
            'quinquenioMeritos' => $quinquenioSolicitud->getQuinquenioMeritos(),
        ]);
    }
}