<?php

namespace App\Controller\Quinque;

use App\Entity\Quinque\SolicitudEstado;
use App\Form\Quinque\SolicitudEstadoType;
use App\Repository\Quinque\SolicitudEstadoRepository;
use App\Service\MessageGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('intranet/quinque/admin/solicitud_estado', name: 'intranet_quinque_admin_solicitud_estado_')]
class SolicitudEstadoController extends AbstractController
{
    
    public function __construct(
        private readonly MessageGenerator $generator,
        private readonly SolicitudEstadoRepository $solicitudEstadoRepository
    ) {}

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('intranet/quinque/admin/solicitud_estado/index.html.twig', [
            'solicitud_estados' => $this->solicitudEstadoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('admin');
        $solicitudEstado = new SolicitudEstado();
        $form = $this->createForm(SolicitudEstadoType::class, $solicitudEstado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->solicitudEstadoRepository->save($solicitudEstado, true);
            $this->generator->logAndFlash('info', 'Nuevo estado de solicitud creado', [
                'id' => $solicitudEstado->getId(),
            ]);

            return $this->redirectToRoute('intranet_quinque_admin_solicitud_estado_index');
        }

        return $this->render('intranet/quinque/admin/solicitud_estado/new.html.twig', [
            'solicitud_estado' => $solicitudEstado,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'show', methods: ['GET'])]
    public function show(SolicitudEstado $solicitudEstado): Response
    {
        $this->denyAccessUnlessGranted('admin');
        
        return $this->render('intranet/quinque/admin/solicitud_estado/show.html.twig', [
            'solicitud_estado' => $solicitudEstado,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SolicitudEstado $solicitudEstado): Response
    {
        $this->denyAccessUnlessGranted('admin');
        $form = $this->createForm(SolicitudEstadoType::class, $solicitudEstado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->solicitudEstadoRepository->save($solicitudEstado, true);
            $this->generator->logAndFlash('info', 'Estado de solicitud actualizado', [
                'id' => $solicitudEstado->getId(),
            ]);

            return $this->redirectToRoute('intranet_quinque_admin_solicitud_estado_index');
        }

        return $this->render('intranet/quinque/admin/solicitud_estado/edit.html.twig', [
            'solicitud_estado' => $solicitudEstado,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, SolicitudEstado $solicitudEstado): Response
    {
        $this->denyAccessUnlessGranted('admin');
        if ($this->isCsrfTokenValid('delete'.$solicitudEstado->getId(), $request->request->get('_token'))) {
            $this->solicitudEstadoRepository->remove($solicitudEstado, true);
            $this->generator->logAndFlash('info', 'Estado de solicitud eliminado');
        }

        return $this->redirectToRoute('intranet_quinque_admin_solicitud_estado_index');
    }
}
