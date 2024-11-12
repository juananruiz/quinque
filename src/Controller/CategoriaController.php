<?php

namespace App\Controller;

use App\Entity\Categoria;
use App\Form\CategoriaType;
use App\Repository\CategoriaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/categoria')]
class CategoriaController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private CategoriaRepository $categoriaRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        CategoriaRepository $categoriaRepository
    ) {
        $this->entityManager = $entityManager;
        $this->categoriaRepository = $categoriaRepository;
    }

    #[Route('/', name: 'categoria_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('categoria/index.html.twig', [
            'categorias' => $this->categoriaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'categoria_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $categoria = new Categoria();
        $form = $this->createForm(CategoriaType::class, $categoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($categoria);
            $this->entityManager->flush();

            return $this->redirectToRoute('categoria_index');
        }

        return $this->render('categoria/new.html.twig', [
            'categoria' => $categoria,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'categoria_show', methods: ['GET'])]
    public function show(Categoria $categoria): Response
    {
        return $this->render('categoria/show.html.twig', [
            'categoria' => $categoria,
        ]);
    }

    #[Route('/{id}/edit', name: 'categoria_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categoria $categoria): Response
    {
        $form = $this->createForm(CategoriaType::class, $categoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('categoria_index');
        }

        return $this->render('categoria/edit.html.twig', [
            'categoria' => $categoria,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'categoria_delete', methods: ['POST'])]
    public function delete(Request $request, Categoria $categoria): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categoria->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($categoria);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('categoria_index');
    }
}
