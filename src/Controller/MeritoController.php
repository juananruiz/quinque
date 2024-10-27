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

/**
 * Controller for managing Merito entities.
 */
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
        
        // Establecer los datos manualmente
        $merito->setOrganismo($request->request->get('organismo'));
        $merito->setCategoriaId($request->request->get('categoriaId'));
        $merito->setFechaInicio(new \DateTime($request->request->get('fechaInicio')));
        $merito->setFechaFin(new \DateTime($request->request->get('fechaFin')));
        $merito->setEstado($request->request->get('estado'));

        // Establecer la solicitud
        $solicitudId = $request->request->get('solicitud_id');
        $solicitud = $this->solicitudRepository->find($solicitudId);

        if (!$solicitud) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Solicitud no encontrada',
            ]);
        }

        $merito->setSolicitud($solicitud);

        try {
            $this->entityManager->persist($merito);
            $this->entityManager->flush();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Mérito guardado correctamente',
                'redirect' => $this->generateUrl('solicitud_show', ['id' => $merito->getSolicitud()->getId()])
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Error al guardar el mérito: ' . $e->getMessage(),
            ]);
        }
    }

    #[Route('/merito/edit/{id}', name: 'merito_edit', methods: ['POST'])]
    public function edit(Merito $merito, Request $request): Response
    {
        try {
            // Actualizar los datos manualmente
            $merito->setOrganismo($request->request->get('organismo'));
            $merito->setCategoriaId($request->request->get('categoriaId'));
            $merito->setFechaInicio(new \DateTime($request->request->get('fechaInicio')));
            $merito->setFechaFin(new \DateTime($request->request->get('fechaFin')));
            $merito->setEstado($request->request->get('estado'));

            $this->entityManager->flush();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Mérito actualizado correctamente',
                'redirect' => $this->generateUrl('solicitud_show', ['id' => $merito->getSolicitud()->getId()])
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Error al actualizar el mérito: ' . $e->getMessage()
            ]);
        }
    }

    #[Route('/merito/{id}/edit', name: 'merito_edit_data', methods: ['GET'])]
    public function getEditData(Merito $merito): JsonResponse
    {
        return new JsonResponse([
            'id' => $merito->getId(),
            'organismo' => $merito->getOrganismo(),
            'categoriaId' => $merito->getCategoriaId(),
            'fechaInicio' => $merito->getFechaInicio()->format('Y-m-d'),
            'fechaFin' => $merito->getFechaFin()->format('Y-m-d'),
            'estado' => $merito->getEstado(),
        ]);
    }
}
