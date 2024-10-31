<?php
/** 
 * Src/Controller/SolicitudController.php 
 */

namespace App\Controller;

use App\Entity\Merito;
use App\Entity\Solicitud;
use App\Form\MeritoType;
use App\Form\SolicitudType;
use App\Repository\PersonaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for handling Solicitud related actions.
 */
class SolicitudController extends AbstractController
{
    private $_personaRepository;
    private $_entityManager;

    public function __construct(PersonaRepository $personaRepository, EntityManagerInterface $entityManager)
    {
        $this->_personaRepository = $personaRepository;
        $this->_entityManager = $entityManager;
    }
    
    #[Route('/solicitud/{id}', name: 'solicitud_show')]
    public function show(Solicitud $solicitud): Response
    {
        $merito = new Merito();
        $form = $this->createForm(MeritoType::class, $merito);

        return $this->render(
            'solicitud/show.html.twig', 
            [
                'solicitud' => $solicitud,
                'meritos' => $solicitud->getMeritos(),
                'persona' => $solicitud->getPersona(),
                'form' => $form->createView(),
            ]
        );
    }

    #[Route('/solicitud/{personaId}/new', name: 'solicitud_new')]
    public function new(Request $request, $personaId): Response
    {
        $solicitud = new Solicitud();
        
        // Set the persona associated with the solicitud
        $persona = $this->_personaRepository->find($personaId);
        $solicitud->setPersona($persona);

        $form = $this->createForm(SolicitudType::class, $solicitud);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Aquí puedes establecer la persona asociada a la solicitud
            $persona = $this->_personaRepository->find($personaId);
            $solicitud->setPersona($persona);

            $this->_entityManager->persist($solicitud);
            $this->_entityManager->flush();

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
