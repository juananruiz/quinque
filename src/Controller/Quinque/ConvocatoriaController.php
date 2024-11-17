<?php

namespace App\Controller\Quinque;

use App\Entity\Quinque\Convocatoria;
use App\Form\Quinque\ConvocatoriaType;
use App\Repository\Quinque\ConvocatoriaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/convocatoria')]
class ConvocatoriaController extends AbstractController
{
    #[Route('/', name: 'quinque_convocatoria_index', methods: ['GET'])]
    public function index(ConvocatoriaRepository $convocatoriaRepository): Response
    {
        return $this->render('intranet/quinque/admin/convocatoria/index.html.twig', [
            'convocatorias' => $convocatoriaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'quinque_convocatoria_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $convocatoria = new Convocatoria();
        $form = $this->createForm(ConvocatoriaType::class, $convocatoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($convocatoria);
            $entityManager->flush();

            return $this->redirectToRoute('quinque_convocatoria_index');
        }

        return $this->render('intranet/quinque/admin/convocatoria/new.html.twig', [
            'convocatoria' => $convocatoria,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'quinque_convocatoria_show', methods: ['GET'])]
    public function show(Convocatoria $convocatoria): Response
    {
        return $this->render('intranet/quinque/admin/convocatoria/show.html.twig', [
            'convocatoria' => $convocatoria,
        ]);
    }

    #[Route('/{id}/edit', name: 'quinque_convocatoria_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Convocatoria $convocatoria, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ConvocatoriaType::class, $convocatoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('quinque_convocatoria_index');
        }

        return $this->render('intranet/quinque/admin/convocatoria/edit.html.twig', [
            'convocatoria' => $convocatoria,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'quinque_convocatoria_delete', methods: ['POST'])]
    public function delete(Request $request, Convocatoria $convocatoria, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$convocatoria->getId(), $request->request->get('_token'))) {
            $entityManager->remove($convocatoria);
            $entityManager->flush();
        }

        return $this->redirectToRoute('quinque_convocatoria_index');
    }
}
