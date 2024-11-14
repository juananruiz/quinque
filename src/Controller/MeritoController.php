<?php

/**
 * Controller for managing Merito entities.
 *
 * path: src/Controller/MeritoController.php 
 **/

namespace App\Controller;

use App\Entity\Estado;
use App\Entity\Categoria;
use App\Entity\Merito;
use App\Entity\Solicitud;
use App\Form\MeritoType;
use App\Repository\SolicitudRepository;
use App\Repository\EstadoRepository;
use App\Repository\CategoriaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller for managing Merito entities.
 */
class MeritoController extends AbstractController
{
    private $solicitudRepository;
    private $entityManager;
    private EstadoRepository $estadoRepository;
	private CategoriaRepository $categoriaRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        SolicitudRepository $solicitudRepository,
        EstadoRepository $estadoRepository,
		CategoriaRepository $categoriaRepository
    ) {
        $this->entityManager = $entityManager;
        $this->solicitudRepository = $solicitudRepository;
        $this->estadoRepository = $estadoRepository;
		$this->categoriaRepository = $categoriaRepository;
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

            return $this->redirectToRoute(
                'solicitud_show',
                ['id' => $merito->getSolicitud()->getId()]
            );
        }

        return $this->render(
            'merito/add.html.twig',
            ['form' => $form->createView(),]
        );
    }

    #[Route('/merito/save', name: 'merito_save', methods: ['POST'])]
    public function save(Request $request): Response
    {
        $merito = new Merito();
        // Recojo los datos "a mano"
        $merito->setOrganismo($request->request->get('organismo'));
        $categoria = $this->entityManager->getRepository(Categoria::class)->find($request->request->get('categoriaId'));
		if (!$categoria) {
			return new JsonResponse([
				'status' => 'error',
				'message' => 'Categoría no encontrada'
			], 404);
		}
		$merito->setFechaInicio(
            new \DateTime($request->request->get('fechaInicio'))
        );
        $merito->setFechaFin(new \DateTime($request->request->get('fechaFin')));
		$estado = $this->entityManager->getRepository(Estado::class)->find($request->request->get('estadoId'));
		if (!$estado) {
			return new JsonResponse([
				'status' => 'error',
				'message' => 'Estado no encontrado'
			], 404);
		}
		$merito->setEstado($estado);
        // Recupera la solicitud
        $solicitudId = $request->request->get('solicitud_id');
        $solicitud = $this->solicitudRepository->find($solicitudId);
        if (!$solicitud) {
            return new JsonResponse(
                [
                    'status' => 'error',
                    'message' => 'Solicitud no encontrada',
                ]
            );
        }
        $merito->setSolicitud($solicitud);

        // Check for overlapping date ranges
        if ($this->isDateRangeOverlapping($solicitud, $merito->getFechaInicio(), $merito->getFechaFin())) {
            $estado = $this->entityManager->getRepository(Estado::class)->find(5);
            $merito->setEstado($estado);
            $this->addFlash('warning', 'Las fechas del mérito se solapan con otro mérito existente.');
        }

        try {
            $this->entityManager->persist($merito);
            $this->entityManager->flush();

            return new JsonResponse(
                [
                    'status' => 'success',
                    'message' => 'Mérito guardado correctamente',
                    'redirect' => $this->generateUrl(
                        'solicitud_show',
                        ['id' => $merito->getSolicitud()->getId()]
                    )
                ]
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                [
                    'status' => 'error',
                    'message' => 'Error al guardar el mérito: ' . $e->getMessage(),
                ]
            );
        }
    }

    /**
     * Updates a Merito entity.
     * 
     * @param Merito  $merito  The Merito entity to update.
     * @param Request $request The HTTP request object.
     * 
     * @return Response The JSON response indicating success or failure.
     */
    #[Route('/merito/edit/{id}', name: 'merito_edit', methods: ['POST'])]
    public function edit(Merito $merito, Request $request): Response
    {
        try {
            // Actualizar los datos manualmente
            $merito->setOrganismo($request->request->get('organismo'));
            $merito->setCategoria($request->request->get('categoriaId'));
            $merito->setFechaInicio(new \DateTime($request->request->get('fechaInicio')));
            $merito->setFechaFin(new \DateTime($request->request->get('fechaFin')));
            $merito->setEstado($request->request->get('estadoId'));

            // Check for overlapping date ranges
            $solicitud = $merito->getSolicitud();
            if ($this->isDateRangeOverlapping($solicitud, $merito->getFechaInicio(), $merito->getFechaFin(), $merito)) {
                $estado = $this->entityManager->getRepository(Estado::class)->find(5);
                $merito->setEstado($estado);
                $this->addFlash('warning', 'Las fechas del mérito se solapan con otro mérito existente.');
            }

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
    /**
     * Retrieves the edit data for a given Merito entity.
     * 
     * @param Merito $merito The Merito entity to retrieve data for.
     * 
     * @return JsonResponse The JSON response containing the Merito data.
     */
    #[Route('/merito/{id}/edit', name: 'merito_edit_data', methods: ['GET'])]
    public function getEditData(Merito $merito): JsonResponse
    {
        return new JsonResponse(
            [
                'id' => $merito->getId(),
                'organismo' => $merito->getOrganismo(),
                'categoria' => $merito->getCategoria()->getId(),
                'fechaInicio' => $merito->getFechaInicio()->format('Y-m-d'),
                'fechaFin' => $merito->getFechaFin()->format('Y-m-d'),
                'estado' => $merito->getEstado()->getId(),
            ]
        );
    }
    /**
     * Deletes a Merito entity by its ID.
     * 
     * @param int $id The ID of the Merito entity to delete.
     *
     * @return JsonResponse
     */
    #[Route('/merito/delete/{id}', name: 'merito_delete', methods: ['POST'])]
    public function delete(Merito $merito): JsonResponse
    {
        try {
            $solicitudId = $merito->getSolicitud()->getId();
            $this->entityManager->remove($merito);
            $this->entityManager->flush();

            return new JsonResponse(
                [
                    'success' => true,
                    'message' => 'Mérito borrado correctamente'
                ]
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => 'Error al borrar el mérito'
                ],
                500
            );
        }
    }

    /**
     * Checks if the given date range overlaps with any existing merits in the solicitud.
     *
     * @param Solicitud $solicitud The solicitud entity.
     * @param \DateTimeInterface $fechaInicio The start date of the new merit.
     * @param \DateTimeInterface $fechaFin The end date of the new merit.
     *
     * @return bool True if the date range overlaps, false otherwise.
     */
    private function isDateRangeOverlapping(Solicitud $solicitud, \DateTimeInterface $fechaInicio, \DateTimeInterface $fechaFin, ?Merito $currentMerito = null): bool
    {
        foreach ($solicitud->getMeritos() as $existingMerito) {
            if ($currentMerito && $existingMerito->getId() === $currentMerito->getId()) {
                continue;
            }
            if (
                ($fechaInicio >= $existingMerito->getFechaInicio() && $fechaInicio <= $existingMerito->getFechaFin()) ||
                ($fechaFin >= $existingMerito->getFechaInicio() && $fechaFin <= $existingMerito->getFechaFin()) ||
                ($fechaInicio <= $existingMerito->getFechaInicio() && $fechaFin >= $existingMerito->getFechaFin())
            ) {
                return true;
            }
        }

        return false;
    }
}
