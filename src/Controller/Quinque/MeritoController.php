<?php

/**
 * Controller for managing Merito entities.
 *
 * path: src/Controller/Quinque/MeritoController.php
 **/

namespace App\Controller\Quinque;

use App\Entity\Quinque\Categoria;
use App\Entity\Quinque\Merito;
use App\Entity\Quinque\MeritoEstado;
use App\Entity\Quinque\Solicitud;
use App\Form\Quinque\MeritoType;
use App\Repository\Quinque\CategoriaRepository;
use App\Repository\Quinque\MeritoEstadoRepository;
use App\Repository\Quinque\MeritoRepository;
use App\Repository\Quinque\SolicitudRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for managing Merito entities.
 */
#[Route('/merito')]
class MeritoController extends AbstractController
{
    private $solicitudRepository;
    private $entityManager;
    private MeritoEstadoRepository $meritoEstadoRepository;
    private CategoriaRepository $categoriaRepository;
    private MeritoRepository $meritoRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        SolicitudRepository $solicitudRepository,
        MeritoEstadoRepository $meritoEstadoRepository,
        CategoriaRepository $categoriaRepository,
        MeritoRepository $meritoRepository,
    ) {
        $this->entityManager = $entityManager;
        $this->solicitudRepository = $solicitudRepository;
        $this->meritoEstadoRepository = $meritoEstadoRepository;
        $this->categoriaRepository = $categoriaRepository;
        $this->meritoRepository = $meritoRepository;
    }

    #[Route('/add', name: 'quinque_merito_add')]
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
                'quinque_solicitud_show',
                ['id' => $merito->getSolicitud()->getId()]
            );
        }

        return $this->render(
            'intranet/quinque/admin/merito/add.html.twig',
            ['form' => $form->createView()]
        );
    }

    #[Route('/save/{solicitudId}', name: 'quinque_merito_save', methods: ['POST'])]
    public function save(Request $request, int $solicitudId): JsonResponse
    {
        $merito = new Merito();
        $solicitud = $this->entityManager->getRepository(Solicitud::class)->find($solicitudId);
        
        if (!$solicitud) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Solicitud no encontrada'
            ], 404);
        }

        $form = $this->createForm(MeritoType::class, $merito);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $merito->setSolicitud($solicitud);
                $merito->setCreatedAt(new \DateTime());
                
                // Verificar solapamiento de fechas
                if ($this->isDateRangeOverlapping($solicitud, $merito->getFechaInicio(), $merito->getFechaFin())) {
                    $estado = $this->meritoEstadoRepository->find(5); // Estado solapado
                    $merito->setEstado($estado);
                }

                $this->entityManager->persist($merito);
                $this->entityManager->flush();

                $response = [
                    'status' => 'success',
                    'message' => 'Mérito guardado correctamente',
                    'redirect' => $this->generateUrl('quinque_solicitud_show', ['id' => $solicitudId])
                ];

                if ($merito->getEstado()->getId() === 5) {
                    $response['warning'] = 'El mérito se ha guardado pero las fechas se solapan con otro mérito existente.';
                }

                return new JsonResponse($response);
            } catch (\Exception $e) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Error al guardar el mérito: ' . $e->getMessage()
                ]);
            }
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return new JsonResponse([
            'status' => 'error',
            'message' => 'Error en el formulario',
            'errors' => $errors
        ]);
    }

    #[Route('/edit/{id}', name: 'quinque_merito_edit', methods: ['POST'])]
    public function edit(Merito $merito, Request $request): JsonResponse
    {
        try {
            $form = $this->createForm(MeritoType::class, $merito);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Check for overlapping date ranges
                $solicitud = $merito->getSolicitud();
                if ($this->isDateRangeOverlapping($solicitud, $merito->getFechaInicio(), $merito->getFechaFin(), $merito)) {
                    $estado = $this->meritoEstadoRepository->find(5);
                    $merito->setEstado($estado);
                }

                $this->entityManager->flush();

                $response = [
                    'status' => 'success',
                    'message' => 'Mérito actualizado correctamente',
                    'redirect' => $this->generateUrl('quinque_solicitud_show', ['id' => $merito->getSolicitud()->getId()]),
                ];

                if ($merito->getEstado()->getId() === 5) {
                    $response['warning'] = 'El mérito se ha actualizado pero las fechas se solapan con otro mérito existente.';
                }

                return new JsonResponse($response);
            }

            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }

            return new JsonResponse([
                'status' => 'error',
                'message' => 'Error en el formulario',
                'errors' => $errors
            ], 400);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Error al actualizar el mérito: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Retrieves the edit data for a given Merito entity.
     *
     * @param Merito $merito the Merito entity to retrieve data for
     *
     * @return JsonResponse the JSON response containing the Merito data
     */
    #[Route('/{id}/edit', name: 'quinque_merito_edit_data', methods: ['GET'])]
    public function getEditData(Merito $merito): JsonResponse
    {
        try {
            return new JsonResponse([
                'status' => 'success',
                'merito' => [
                    'id' => $merito->getId(),
                    'organismo' => $merito->getOrganismo(),
                    'categoriaId' => $merito->getCategoria()->getId(),
                    'fechaInicio' => $merito->getFechaInicio()->format('Y-m-d'),
                    'fechaFin' => $merito->getFechaFin()->format('Y-m-d'),
                    'estadoId' => $merito->getEstado()->getId(),
                    'dedicacion' => $merito->getDedicacion(),
                    'observaciones' => $merito->getObservaciones()
                ]
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Error al cargar los datos del mérito: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Deletes a Merito entity by its ID.
     */
    #[Route('/delete/{id}', name: 'quinque_merito_delete', methods: ['DELETE', 'POST'])]
    public function delete(Merito $merito): JsonResponse
    {
        try {
            $solicitudId = $merito->getSolicitud()->getId();
            $this->entityManager->remove($merito);
            $this->entityManager->flush();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Mérito borrado correctamente',
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Error al borrar el mérito: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function isDateRangeOverlapping(Solicitud $solicitud, \DateTime $startDate, \DateTime $endDate, ?Merito $excludeMerito = null): bool
    {
        foreach ($solicitud->getMeritos() as $existingMerito) {
            if ($excludeMerito && $existingMerito->getId() === $excludeMerito->getId()) {
                continue;
            }
            if (
                ($startDate <= $existingMerito->getFechaFin() && $endDate >= $existingMerito->getFechaInicio()) ||
                ($existingMerito->getFechaInicio() <= $endDate && $existingMerito->getFechaFin() >= $startDate)
            ) {
                return true;
            }
        }
        return false;
    }
}
