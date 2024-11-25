<?php

namespace App\Controller\Quinque;

use App\Entity\Quinque\Convocatoria;
use App\Form\Quinque\ConvocatoriaType;
use App\Repository\Quinque\ConvocatoriaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/convocatoria', name: 'intranet_quinque_admin_convocatoria_')]
class ConvocatoriaController extends AbstractController
{
    public function __construct(
        private readonly ConvocatoriaRepository $convocatoriaRepository,
    ) {
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('intranet/quinque/admin/convocatoria/index.html.twig', [
            'convocatorias' => $this->convocatoriaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $convocatoria = new Convocatoria();
        $form = $this->createForm(ConvocatoriaType::class, $convocatoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->convocatoriaRepository->save($convocatoria, true);

            return $this->redirectToRoute('intranet_quinque_admin_convocatoria_index');
        }

        return $this->render('intranet/quinque/admin/convocatoria/new.html.twig', [
            'convocatoria' => $convocatoria,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'show', methods: ['GET'])]
    public function show(Convocatoria $convocatoria): Response
    {
        return $this->render('intranet/quinque/admin/convocatoria/show.html.twig', [
            'convocatoria' => $convocatoria,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Convocatoria $convocatoria): Response
    {
        $form = $this->createForm(ConvocatoriaType::class, $convocatoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->convocatoriaRepository->save($convocatoria, true);

            return $this->redirectToRoute('intranet_quinque_admin_convocatoria_index');
        }

        return $this->render('intranet/quinque/admin/convocatoria/edit.html.twig', [
            'convocatoria' => $convocatoria,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Convocatoria $convocatoria): Response
    {
        if ($this->isCsrfTokenValid('delete'.$convocatoria->getId(), $request->request->get('_token'))) {
            $this->convocatoriaRepository->remove($convocatoria, true);
        }

        return $this->redirectToRoute('intranet_quinque_admin_convocatoria_index');
    }
}
