<?php

namespace App\Controller\Quinque;

use App\Entity\Quinque\Categoria;
use App\Form\Quinque\CategoriaType;
use App\Repository\Quinque\CategoriaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/categoria', name: 'intranet_quinque_admin_categoria_')]
class CategoriaController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private CategoriaRepository $categoriaRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        CategoriaRepository $categoriaRepository,
    ) {
        $this->entityManager = $entityManager;
        $this->categoriaRepository = $categoriaRepository;
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('intranet/quinque/admin/categoria/index.html.twig', [
            'categorias' => $this->categoriaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $categoria = new Categoria();
        $form = $this->createForm(CategoriaType::class, $categoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($categoria);
            $this->entityManager->flush();

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
        return $this->render('intranet/quinque/admin/categoria/show.html.twig', [
            'categoria' => $categoria,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categoria $categoria): Response
    {
        $form = $this->createForm(CategoriaType::class, $categoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

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
        if ($this->isCsrfTokenValid('delete'.$categoria->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($categoria);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('intranet_quinque_admin_categoria_index');
    }
}
