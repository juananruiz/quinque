<?php

namespace App\Controller\Peticion;

use App\Entity\Peticion\Comentario;
use App\Entity\Peticion\Solicitud;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;

#[Route('/intranet/peticion/gestor/comentario')]
class ComentarioController extends AbstractController
{
    #[Route('/{id}/edit', name: 'app_peticion_comentario_edit', methods: ['POST'])]
    public function edit(Request $request, Comentario $comentario, EntityManagerInterface $entityManager): Response
    {
        $content = $request->request->get('content');
        
        if (!$content) {
            return new JsonResponse(['error' => 'El contenido no puede estar vacío'], Response::HTTP_BAD_REQUEST);
        }

        $comentario->setContenido($content);
        $comentario->setModifiedAt(new \DateTimeImmutable());
        
        try {
            $entityManager->flush();
            
            return new JsonResponse([
                'success' => true,
                'message' => 'Comentario actualizado correctamente',
                'content' => $comentario->getContenido(),
                'modifiedAt' => $comentario->getModifiedAt()->format('d/m/Y H:i')
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Error al actualizar el comentario'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/new/{solicitud_id}', name: 'app_peticion_comentario_new', methods: ['POST'])]
    #[IsGranted('ROLE_PETICION_GESTOR')]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager, 
        #[MapEntity(id: 'solicitud_id')] Solicitud $solicitud
    ): Response
    {
        $content = $request->request->get('content');
        
        if (!$content) {
            return new JsonResponse(['error' => 'El contenido del comentario no puede estar vacío'], Response::HTTP_BAD_REQUEST);
        }

        $comentario = new Comentario();
        $comentario->setContenido($content);
        $comentario->setCreatedAt(new \DateTimeImmutable());
        $comentario->setSolicitud($solicitud);

        try {
            $entityManager->persist($comentario);
            $entityManager->flush();
            
            return new JsonResponse([
                'success' => true,
                'message' => 'Comentario añadido correctamente',
                'content' => $comentario->getContenido(),
                'createdAt' => $comentario->getCreatedAt()->format('d/m/Y H:i'),
                'id' => $comentario->getId()
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Error al crear el comentario'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}/delete', name: 'app_peticion_comentario_delete', methods: ['POST'])]
    //#[IsGranted('ROLE_PETICION_GESTOR')]
    public function delete(Request $request, Comentario $comentario, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comentario->getId(), $request->request->get('_token'))) {
            $solicitudId = $comentario->getSolicitud()->getId();
            
            try {
                $entityManager->remove($comentario);
                $entityManager->flush();
                $this->addFlash('success', 'Comentario eliminado correctamente');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Error al eliminar el comentario');
            }

            return $this->redirectToRoute('app_peticion_solicitud_show', ['id' => $solicitudId]);
        }

        return $this->redirectToRoute('app_peticion_solicitud_show', ['id' => $comentario->getSolicitud()->getId()]);
    }
}
