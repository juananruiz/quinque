<?php

namespace App\Controller\Quinque;

use App\Entity\Quinque\Departamento;
use App\Form\Quinque\DepartamentoType;
use App\Repository\Quinque\DepartamentoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/departamento')]
class DepartamentoController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private DepartamentoRepository $departamentoRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        DepartamentoRepository $departamentoRepository,
    ) {
        $this->entityManager = $entityManager;
        $this->departamentoRepository = $departamentoRepository;
    }

    #[Route('/departamento', name: 'quinque_departamento_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('intranet/quinque/admin/departamento/index.html.twig', [
            'departamentos' => $this->departamentoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'quinque_departamento_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $departamento = new Departamento();
        $form = $this->createForm(DepartamentoType::class, $departamento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($departamento);
            $this->entityManager->flush();

            return $this->redirectToRoute('quinque_departamento_index');
        }

        return $this->render('intranet/quinque/admin/departamento/new.html.twig', [
            'departamento' => $departamento,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'quinque_departamento_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Departamento $departamento): Response
    {
        $form = $this->createForm(DepartamentoType::class, $departamento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('quinque_departamento_index');
        }

        return $this->render('intranet/quinque/admin/departamento/edit.html.twig', [
            'departamento' => $departamento,
            'form' => $form,
        ]);
    }
}
