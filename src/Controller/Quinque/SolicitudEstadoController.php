<?php

namespace App\Controller\Quinque;

use App\Entity\Quinque\SolicitudEstado;
use App\Form\Quinque\SolicitudEstadoType;
use App\Repository\Quinque\SolicitudEstadoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/solicitud_estado')]
class SolicitudEstadoController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private SolicitudEstadoRepository $solicitudEstadoRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        SolicitudEstadoRepository $solicitudEstadoRepository,
    ) {
        $this->entityManager = $entityManager;
        $this->solicitudEstadoRepository = $solicitudEstadoRepository;
    }

    #[Route('/', name: 'quinque_solicitud_estado_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('intranet/quinque/admin/solicitud_estado/index.html.twig', [
            'solicitud_estados' => $this->solicitudEstadoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'quinque_solicitud_estado_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $solicitudEstado = new SolicitudEstado();
        $form = $this->createForm(SolicitudEstadoType::class, $solicitudEstado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($solicitudEstado);
            $this->entityManager->flush();

            return $this->redirectToRoute('quinque_solicitud_estado_index');
        }

        return $this->render('intranet/quinque/admin/solicitud_estado/new.html.twig', [
            'solicitud_estado' => $solicitudEstado,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'quinque_solicitud_estado_show', methods: ['GET'])]
    public function show(SolicitudEstado $solicitudEstado): Response
    {
        return $this->render('intranet/quinque/admin/solicitud_estado/show.html.twig', [
            'solicitud_estado' => $solicitudEstado,
        ]);
    }

    #[Route('/edit/{id}', name: 'quinque_solicitud_estado_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SolicitudEstado $solicitudEstado): Response
    {
        $form = $this->createForm(SolicitudEstadoType::class, $solicitudEstado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('quinque_solicitud_estado_index');
        }

        return $this->render('intranet/quinque/admin/solicitud_estado/edit.html.twig', [
            'solicitud_estado' => $solicitudEstado,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'quinque_solicitud_estado_delete', methods: ['POST'])]
    public function delete(Request $request, SolicitudEstado $solicitudEstado): Response
    {
        if ($this->isCsrfTokenValid('delete'.$solicitudEstado->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($solicitudEstado);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('quinque_solicitud_estado_index');
    }
}
