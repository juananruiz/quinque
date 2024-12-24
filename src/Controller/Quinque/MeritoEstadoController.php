<?php

namespace App\Controller\Quinque;

use App\Entity\Quinque\MeritoEstado;
use App\Form\Quinque\MeritoEstadoType;
use App\Repository\Quinque\MeritoEstadoRepository;
use App\Service\MessageGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('intranet/quinque/admin/merito_estado', name: 'intranet_quinque_admin_merito_estado_')]
class MeritoEstadoController extends AbstractController
{
    public function __construct(
        private MeritoEstadoRepository $meritoEstadoRepository,
        private MessageGenerator $generator
    ) {
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(MeritoEstadoRepository $meritoEstadoRepository): Response
    {
        $this->denyAccessUnlessGranted('admin');
        
        return $this->render('intranet/quinque/admin/merito_estado/index.html.twig', [
            'merito_estados' => $meritoEstadoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('admin');
        $meritoEstado = new MeritoEstado();
        $form = $this->createForm(MeritoEstadoType::class, $meritoEstado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->meritoEstadoRepository->save($meritoEstado, true);
            $this->generator->logAndFlash('info', 'Nuevo estado de mérito creado', [
                'id' => $meritoEstado->getId(),
            ]);

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
        $this->denyAccessUnlessGranted('admin');
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
    public function edit(Request $request, MeritoEstado $meritoEstado): Response
    {
        $this->denyAccessUnlessGranted('admin');
        $form = $this->createForm(MeritoEstadoType::class, $meritoEstado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->meritoEstadoRepository->save($meritoEstado, true);
            $this->generator->logAndFlash('info', 'Estado de mérito actualizado', [
                'id' => $meritoEstado->getId(),
            ]);

            return $this->redirectToRoute('intranet_quinque_admin_merito_estado_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('intranet/quinque/admin/merito_estado/edit.html.twig', [
            'merito_estado' => $meritoEstado,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, MeritoEstado $meritoEstado): Response
    {
        $this->denyAccessUnlessGranted('admin');
        if ($this->isCsrfTokenValid('delete'.$meritoEstado->getId(), $request->request->get('_token'))) {
            $this->meritoEstadoRepository->remove($meritoEstado, true);
            $this->generator->logAndFlash('info', 'Estado de mérito eliminado');
        }

        return $this->redirectToRoute('intranet_quinque_admin_merito_estado_index', [], Response::HTTP_SEE_OTHER);
    }
}
