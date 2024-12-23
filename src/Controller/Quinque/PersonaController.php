<?php
/**
 * PersonaController.php.
 *
 * This file contains the PersonaController class, which manages Persona entities.
 *
 * @author  Juanan Ruiz <juanan@us.es>
 */

namespace App\Controller\Quinque;

use App\Entity\Quinque\Persona;
use App\Form\Quinque\PersonaType;
use App\Repository\Quinque\PersonaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('intranet/quinque/admin/persona', name:'intranet_quinque_admin_persona_')]
/**
 * Controller for managing Persona entities.
 */
final class PersonaController extends AbstractController
{
    /**
     * Displays a list of Persona entities.
     */
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(PersonaRepository $personaRepository): Response
    {
        return $this->render(
            'intranet/quinque/admin/persona/index.html.twig',
            [
                'personas' => $personaRepository->findAll(),
            ]
        );
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $persona = new Persona();
        $form = $this->createForm(PersonaType::class, $persona);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($persona);
            $entityManager->flush();

            return $this->redirectToRoute(
                'intranet_quinque_admin_persona_index',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render(
            'intranet/quinque/admin/persona/new.html.twig',
            [
                'persona' => $persona,
                'form' => $form,
            ]
        );
    }

    #[Route('/{id}/show', name: 'show', methods: ['GET'])]
    public function show(Persona $persona): Response
    {
        return $this->render('intranet/quinque/admin/persona/show.html.twig', [
            'persona' => $persona,
            'solicitudes' => $persona->getSolicitudes(),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Persona $persona, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PersonaType::class, $persona);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('intranet_quinque_admin_persona_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('intranet/quinque/admin/persona/edit.html.twig', [
            'persona' => $persona,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Persona $persona, EntityManagerInterface $entityManager): Response
    {
        // No permitir si la persona tienes solicitudes asociadas,
        // primero habría que borrar aquellas
        if ($persona->getSolicitudes()->count() > 0) {
            $this->addFlash('error', 'No se puede eliminar una persona con solicitudes asociadas');

            return $this->redirectToRoute('intranet_quinque_admin_persona_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid(
            'delete'.$persona->getId(),
            $request->get('_token')
        )
        ) {
            $entityManager->remove($persona);
            $entityManager->flush();
        }

        return $this->redirectToRoute(
            'intranet_quinque_admin_persona_show',
            ['id' => $persona->getId()],
            Response::HTTP_SEE_OTHER);
    }
}
