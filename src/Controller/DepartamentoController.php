<?php

namespace App\Controller;

use App\Entity\Departamento;
use App\Form\DepartamentoType;
use App\Repository\DepartamentoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DepartamentoController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private DepartamentoRepository $departamentoRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        DepartamentoRepository $departamentoRepository
    ) {
        $this->entityManager = $entityManager;
        $this->departamentoRepository = $departamentoRepository;
    }

    #[Route('/departamento', name: 'departamento_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('departamento/index.html.twig', [
            'departamentos' => $this->departamentoRepository->findAll(),
        ]);
    }

    #[Route('/departamento/new', name: 'departamento_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $departamento = new Departamento();
        $form = $this->createForm(DepartamentoType::class, $departamento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($departamento);
            $this->entityManager->flush();

            return $this->redirectToRoute('departamento_index');
        }

        return $this->render('departamento/new.html.twig', [
            'departamento' => $departamento,
            'form' => $form,
        ]);
    }

    #[Route('/departamento/{id}/edit', name: 'departamento_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Departamento $departamento): Response
    {
        $form = $this->createForm(DepartamentoType::class, $departamento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('departamento_index');
        }

        return $this->render('departamento/edit.html.twig', [
            'departamento' => $departamento,
            'form' => $form,
        ]);
    }
}
