<?php
// src/Controller/MeritoController.php
namespace App\Controller;

use App\Entity\Merito;
use App\Entity\Solicitud;
use App\Form\MeritoType;
use App\Repository\SolicitudRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Abbreviation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class MeritoController extends AbstractController
{
    private $solicitudRepository;
    private $entityManager;

    public function __construct(SolicitudRepository $solicitudRepository, EntityManagerInterface $entityManager)
    {
        $this->solicitudRepository = $solicitudRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/merito/add', name: 'merito_add')]
    public function add(Request $request): Response
    {
        $merito = new Merito();
        $form = $this->createForm(MeritoType::class, $merito);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $solicitudId = $request->get('solicitud_id');
            $solicitud = $this->solicitudRepository->find($solicitudId);

            if ($solicitud) {
                $merito->setSolicitud($solicitud);
            }

            $this->entityManager->persist($merito);
            $this->entityManager->flush();

            return $this->redirectToRoute('solicitud_show', ['id' => $merito->getSolicitud()->getId()]);
        }

        return $this->render('merito/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/merito/save', name: 'merito_save', methods: ['POST'])]
    public function save(Request $request): Response
    {
        $merito = new Merito();
        $form = $this->createForm(MeritoType::class, $merito);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $solicitudId = $request->get('solicitud_id');
            $solicitud = $this->solicitudRepository->find($solicitudId);

            if ($solicitud) {
                $merito->setSolicitud($solicitud);
            }

            $this->entityManager->persist($merito);
            $this->entityManager->flush();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Mérito guardado correctamente',
                'redirect' => $this->generateUrl('solicitud_show', ['id' => $merito->getSolicitud()->getId()])
            ]);
        }

        return new JsonResponse([
            'status' => 'error',
            'message' => 'Error al guardar el merito',
            'errors' => (string) $form->getErrors(true, false)
        ]);
    }

    #[Route('/merito/edit/{id}', name: 'merito_edit')]
    public function edit(Merito $merito, Request $request): Response
    {
        $form = $this->createForm(MeritoType::class, $merito);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('solicitud_show', ['id' => $merito->getSolicitud()->getId()]);
        }

        return $this->render('merito/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
