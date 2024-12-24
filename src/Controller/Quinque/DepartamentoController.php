<?php

namespace App\Controller\Quinque;

use App\Entity\Quinque\Departamento;
use App\Form\Quinque\DepartamentoType;
use App\Repository\Quinque\DepartamentoRepository;
use App\Service\MessageGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('intranet/quinque/admin/departamento', name: 'intranet_quinque_admin_departamento_')]
class DepartamentoController extends AbstractController
{
    
    public function __construct(
        private readonly MessageGenerator $generator,
        private readonly DepartamentoRepository $departamentoRepository,
    ) {}

    #[Route('/departamento', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('intranet/quinque/admin/departamento/index.html.twig', [
            'departamentos' => $this->departamentoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('admin');
        $departamento = new Departamento();
        $form = $this->createForm(DepartamentoType::class, $departamento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->departamentoRepository->save($departamento, true);
            $this->generator->logAndFlash('info', 'Nuevo departamento creado', [
                'id' => $departamento->getId(),
            ]); 

            return $this->redirectToRoute('intranet_quinque_admin_departamento_index');
        }

        return $this->render('intranet/quinque/admin/departamento/new.html.twig', [
            'departamento' => $departamento,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Departamento $departamento): Response
    {
        $this->denyAccessUnlessGranted('admin');
        $form = $this->createForm(DepartamentoType::class, $departamento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->departamentoRepository->save($departamento, true);
            $this->generator->logAndFlash('info', 'Departamento editado', [
                'id' => $departamento->getId(),
            ]); 

            return $this->redirectToRoute('intranet_quinque_admin_departamento_index');
        }

        return $this->render('intranet/quinque/admin/departamento/edit.html.twig', [
            'departamento' => $departamento,
            'form' => $form,
        ]);
    }
}
