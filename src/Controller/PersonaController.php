<?php

namespace App\Controller;

use App\Entity\Persona;
use App\Form\PersonaType;
use App\Repository\PersonaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/persona')]
final class PersonaController extends AbstractController
{
    #[Route(name: 'persona_index', methods: ['GET'])]
    public function index(PersonaRepository $personaRepository): Response
    {
        return $this->render('persona/index.html.twig', [
            'personas' => $personaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'persona_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $persona = new Persona();
        $form = $this->createForm(PersonaType::class, $persona);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($persona);
            $entityManager->flush();

            return $this->redirectToRoute('persona_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('persona/new.html.twig', [
            'persona' => $persona,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'persona_show', methods: ['GET'])]
    public function show(Persona $persona): Response
    {
        return $this->render('persona/show.html.twig', [
            'persona' => $persona,
            'quinquenioSolicitudes' => $persona->getQuinquenioSolicitudes(),
        ]);
    }

    #[Route('/{id}/edit', name: 'persona_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Persona $persona, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PersonaType::class, $persona);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('persona_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('persona/edit.html.twig', [
            'persona' => $persona,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'persona_delete', methods: ['POST'])]
    public function delete(Request $request, Persona $persona, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$persona->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($persona);
            $entityManager->flush();
        }

        return $this->redirectToRoute('persona_index', [], Response::HTTP_SEE_OTHER);
    }
}
