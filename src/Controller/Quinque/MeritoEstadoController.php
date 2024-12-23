<?php

namespace App\Controller\Quinque;

use App\Entity\Quinque\MeritoEstado;
use App\Form\Quinque\MeritoEstadoType;
use App\Repository\Quinque\MeritoEstadoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('intranet/quinque/admin/merito_estado', name: 'intranet_quinque_admin_merito_estado_')]
class MeritoEstadoController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(MeritoEstadoRepository $meritoEstadoRepository): Response
    {
        return $this->render('intranet/quinque/admin/merito_estado/index.html.twig', [
            'merito_estados' => $meritoEstadoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $meritoEstado = new MeritoEstado();
        $form = $this->createForm(MeritoEstadoType::class, $meritoEstado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($meritoEstado);
            $entityManager->flush();

            return $this->redirectToRoute('intranet_quinque_admin_merito_estado_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('intranet/quinque/admin/merito_estado/new.html.twig', [
            'merito_estado' => $meritoEstado,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'show', methods: ['GET'])]
    public function show(MeritoEstado $meritoEstado): Response
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('delete', ['id' => $meritoEstado->getId()]))
            ->setMethod('POST')
            ->getForm();

        return $this->render('intranet/quinque/admin/merito_estado/show.html.twig', [
            'merito_estado' => $meritoEstado,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MeritoEstado $meritoEstado, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MeritoEstadoType::class, $meritoEstado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('intranet_quinque_admin_merito_estado_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('intranet/quinque/admin/merito_estado/edit.html.twig', [
            'merito_estado' => $meritoEstado,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, MeritoEstado $meritoEstado, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$meritoEstado->getId(), $request->request->get('_token'))) {
            $entityManager->remove($meritoEstado);
            $entityManager->flush();
        }

        return $this->redirectToRoute('intranet_quinque_admin_merito_estado_index', [], Response::HTTP_SEE_OTHER);
    }
}
