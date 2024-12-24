<?php

namespace App\Controller\Quinque;

use App\Entity\Quinque\Categoria;
use App\Form\Quinque\CategoriaType;
use App\Repository\Quinque\CategoriaRepository;
use App\Service\MessageGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('intranet/quinque/admin/categoria', name: 'intranet_quinque_admin_categoria_')]
class CategoriaController extends AbstractController
{
    public function __construct(
        private readonly MessageGenerator $generator,
        private readonly CategoriaRepository $categoriaRepository,
    ) {
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('admin');

        return $this->render('intranet/quinque/admin/categoria/index.html.twig', [
            'categorias' => $this->categoriaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('admin');
        $categoria = new Categoria();
        $form = $this->createForm(CategoriaType::class, $categoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoriaRepository->save($categoria, true);
            $this->generator->logAndFlash('info', 'Nueva categoría creada', [
                'id' => $categoria->getId(),
            ]);

            return $this->redirectToRoute('intranet_quinque_admin_categoria_index');
        }

        return $this->render('intranet/quinque/admin/categoria/new.html.twig', [
            'categoria' => $categoria,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Categoria $categoria): Response
    {
        $this->denyAccessUnlessGranted('admin');

        return $this->render('intranet/quinque/admin/categoria/show.html.twig', [
            'categoria' => $categoria,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categoria $categoria): Response
    {
        $this->denyAccessUnlessGranted('admin');
        $form = $this->createForm(CategoriaType::class, $categoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoriaRepository->save($categoria, true);
            $this->generator->logAndFlash('info', 'Categoría modificada', [
                'id' => $categoria->getId(),
            ]);

            return $this->redirectToRoute('intranet_quinque_admin_categoria_index');
        }

        return $this->render('intranet/quinque/admin/categoria/edit.html.twig', [
            'categoria' => $categoria,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Categoria $categoria): Response
    {
        $this->denyAccessUnlessGranted('admin');
        $id = $categoria->getId();
        if ($this->isCsrfTokenValid('delete'.$id, $request->request->getString('_token'))) {
            $this->categoriaRepository->remove($categoria, true);
            $this->generator->logAndFlash('info', 'Categoría eliminada');
        }

        return $this->redirectToRoute('intranet_quinque_admin_categoria_index');
    }
}
