<?php

/**
 * Controller for managing Merito entities.
 *
 * path: src/Controller/Quinque/MeritoController.php
 * auhor: Juanan Ruiz <juanan@us.es>
 **/

namespace App\Controller\Quinque;

use App\Entity\Quinque\Merito;
use App\Entity\Quinque\Solicitud;
use App\Form\Quinque\MeritoType;
use App\Repository\Quinque\MeritoRepository;
use App\Repository\Quinque\MeritoEstadoRepository;
use App\Repository\Quinque\SolicitudRepository;
use App\Service\MessageGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for managing Merito entities.
 */
#[Route('intranet/quinque/admin/merito', name: 'intranet_quinque_admin_merito_')]
class MeritoController extends AbstractController
{
    public function __construct(
        private readonly MeritoRepository $meritoRepository,
        private readonly MeritoEstadoRepository $meritoEstadoRepository,
        private readonly SolicitudRepository $solicitudRepository,
        private readonly MessageGenerator $generator
    ) {}

    #[Route('/add', name: 'add')]
    public function add(Request $request): Response
    {
        $this->denyAccessUnlessGranted('admin');
        $merito = new Merito();
        $form = $this->createForm(MeritoType::class, $merito);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $solicitudId = $request->get('solicitud_id');
            $solicitud = $this->solicitudRepository->find($solicitudId);

            if ($solicitud) {
                $merito->setSolicitud($solicitud);
            }

            $this->meritoRepository->save($merito);
            $this->generator->logAndFlash('info', 'Nuevo mérito añadido', [
                'id' => $merito->getId(),
            ]);

            return $this->redirectToRoute(
                'inatranet_quinque_admin_solicitud_show',
                ['id' => $merito->getSolicitud()->getId()]
            );
        }

        return $this->render(
            'intranet/quinque/admin/merito/add.html.twig',
            ['form' => $form->createView()]
        );
    }

    #[Route('/save/{solicitudId}', name: 'save', methods: ['POST'])]
    public function save(Request $request, int $solicitudId): JsonResponse
    {
        $this->denyAccessUnlessGranted('admin');
        $merito = new Merito();
        $solicitud = $this->solicitudRepository->find($solicitudId);
        
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

                $this->meritoRepository->save($merito, true);
                $this->generator->logAndFlash('info', 'Nuevo mérito creado', [
                    'id' => $merito->getId(),
                ]);

                $response = [
                    'status' => 'success',
                    'message' => 'Mérito guardado correctamente',
                    'redirect' => $this->generateUrl('intranet_quinque_admin_solicitud_show', ['id' => $solicitudId])
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

    #[Route('/edit/{id}', name: 'edit', methods: ['POST'])]
    public function edit(Merito $merito, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('admin');
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

                $this->meritoRepository->save($merito, true);
                $this->generator->logAndFlash('info', 'Mérito actualizado', [
                    'id' => $merito->getId(),
                ]);

                $response = [
                    'status' => 'success',
                    'message' => 'Mérito actualizado correctamente',
                    'redirect' => $this->generateUrl('intranet_quinque_admin_solicitud_show', ['id' => $merito->getSolicitud()->getId()]),
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
    #[Route('/{id}/edit', name: 'edit_data', methods: ['GET'])]
    public function getEditData(Merito $merito): JsonResponse
    {
        $this->denyAccessUnlessGranted('admin');
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
    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE', 'POST'])]
    public function delete(Merito $merito): JsonResponse
    {
        $this->denyAccessUnlessGranted('admin');
        try {
            $this->meritoRepository->remove($merito, true);
            $this->generator->logAndFlash('info', 'Mérito borrado');

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
