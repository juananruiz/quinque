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

    /**
     * Constructor for SolicitudController.
     *
     * @param PersonaRepository $personaRepository The persona repository
     * @param EntityManagerInterface $entityManager The entity manager
     */
    public function __construct(PersonaRepository $personaRepository, EntityManagerInterface $entityManager)
    {
        $this->_personaRepository = $personaRepository;
        $this->_entityManager = $entityManager;
    }

    /**
     * Displays a Solicitud entity.
     *
     * @param Solicitud $solicitud The Solicitud entity
     * 
     * @return Response The response object
     */
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
                'totalDiasComputados' => $solicitud->getTotalDiasComputados(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Creates a new Solicitud entity.
     *
     * @param Request $request The HTTP request
     * @param int $personaId The ID of the persona
     *
     * @return Response The response object
     */
    #[Route('/solicitud/new/{personaId}', name: 'solicitud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, int $personaId): Response
    {
        $persona = $this->_personaRepository->find($personaId);
        if (!$persona) {
            throw $this->createNotFoundException('Persona no encontrada');
        }

        $solicitud = new Solicitud();
        $solicitud->setPersona($persona);
        // Set default convocatoria value as current year
        $solicitud->setConvocatoria((new \DateTime())->format('Y'));

        $form = $this->createForm(SolicitudType::class, $solicitud);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->_entityManager->persist($solicitud);
            $this->_entityManager->flush();

            return $this->redirectToRoute(
                'solicitud_show',
                ['id' => $solicitud->getId()]
            );
        }

        return $this->render(
            'solicitud/new.html.twig',
            [
                'solicitud' => $solicitud,
                'form' => $form->createView(),
            ]
        );
    }
}
