<?php
/** 
 * src/Controller/SolicitudController.php 
 */

namespace App\Controller;

use App\Entity\Merito;
use App\Entity\Solicitud;
use App\Form\MeritoType;
use App\Form\SolicitudType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Persona;

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
            'persona' => $solicitud->getPersona(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/solicitud/new/{personaId}', name: 'solicitud_new')]
    public function new(Request $request, $personaId): Response
    {
        $solicitud = new Solicitud();
        $form = $this->createForm(SolicitudType::class, $solicitud);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Aquí puedes establecer la persona asociada a la solicitud
            $persona = $this->getDoctrine()->getRepository(Persona::class)->find($personaId);
            $solicitud->setPersona($persona);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($solicitud);
            $entityManager->flush();

            return $this->redirectToRoute('solicitud_show', ['id' => $solicitud->getId()]);
        }

        return $this->render(
            'solicitud/new.html.twig', 
            [
                'form' => $form->createView(),
            ]
        );
    }
}
