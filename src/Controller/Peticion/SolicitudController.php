<?php

namespace App\Controller\Peticion;

use App\Entity\Peticion\Solicitud;
use App\Form\Peticion\SolicitudType;
use App\Repository\Peticion\SolicitudRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/intranet/peticion/gestor/solicitud')]
class SolicitudController extends AbstractController
{
    #[Route('/', name: 'app_peticion_solicitud_index', methods: ['GET'])]
    public function index(SolicitudRepository $solicitudRepository): Response
    {
        return $this->render('intranet/peticion/gestor/solicitud/index.html.twig', [
            'solicitudes' => $solicitudRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_peticion_solicitud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $solicitud = new Solicitud();
        $form = $this->createForm(SolicitudType::class, $solicitud);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($solicitud);
            $entityManager->flush();

            $this->addFlash('success', 'La solicitud ha sido creada correctamente.');
            return $this->redirectToRoute('app_peticion_solicitud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('intranet/peticion/gestor/solicitud/new.html.twig', [
            'solicitud' => $solicitud,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_peticion_solicitud_show', methods: ['GET'])]
    public function show(Solicitud $solicitud): Response
    {
        return $this->render('intranet/peticion/gestor/solicitud/show.html.twig', [
            'solicitud' => $solicitud,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_peticion_solicitud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Solicitud $solicitud, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SolicitudType::class, $solicitud);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'La solicitud ha sido actualizada correctamente.');
            return $this->redirectToRoute('app_peticion_solicitud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('intranet/peticion/gestor/solicitud/edit.html.twig', [
            'solicitud' => $solicitud,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_peticion_solicitud_delete', methods: ['POST'])]
    public function delete(Request $request, Solicitud $solicitud, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$solicitud->getId(), $request->request->get('_token'))) {
            $entityManager->remove($solicitud);
            $entityManager->flush();
            $this->addFlash('success', 'La solicitud ha sido eliminada correctamente.');
        }

        return $this->redirectToRoute('app_peticion_solicitud_index', [], Response::HTTP_SEE_OTHER);
    }
}
