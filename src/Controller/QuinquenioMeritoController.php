<?php
// src/Controller/QuinquenioMeritoController.php
namespace App\Controller;

use App\Entity\QuinquenioMerito;
use App\Form\QuinquenioMeritoType;
use App\Repository\QuinquenioMeritoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuinquenioMeritoController extends AbstractController
{
    #[Route('/merito/add', name: 'merito_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $merito = new QuinquenioMerito();
        $form = $this->createForm(QuinquenioMeritoType::class, $merito);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($merito);
            $entityManager->flush();

            return $this->redirectToRoute('quinquenio_solicitud_show', ['id' => $merito->getQuinquenioSolicitud()->getId()]);
        }

        return $this->render('quinquenio_merito/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/merito/save', name: 'merito_save', methods: ['POST'])]
    public function save(Request $request, EntityManagerInterface $entityManager): Response
    {
        $merito = new QuinquenioMerito();
        $form = $this->createForm(QuinquenioMeritoType::class, $merito);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($merito);
            $entityManager->flush();

            return $this->json([
                'status' => 'success',
                'message' => 'Mérito guardado correctamente',
                'redirect' => $this->generateUrl('quinquenio_solicitud_show', ['id' => $merito->getQuinquenioSolicitud()->getId()])
            ]);
        }

        return $this->json([
            'status' => 'error',
            'message' => 'Error al guardar el mérito',
            'errors' => (string) $form->getErrors(true, false)
        ]);
    }

    #[Route('/merito/edit/{id}', name: 'merito_edit')]
    public function edit(QuinquenioMerito $merito, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuinquenioMeritoType::class, $merito);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('quinquenio_solicitud_show', ['id' => $merito->getQuinquenioSolicitud()->getId()]);
        }

        return $this->render('quinquenio_merito/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}