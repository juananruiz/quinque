<?php
// src/Controller/EstadoController.php
namespace App\Controller;

use App\Entity\Estado;
use App\Form\EstadoType;
use App\Repository\EstadoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/estado')]
class EstadoController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private EstadoRepository $estadoRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        EstadoRepository $estadoRepository
    ) {
        $this->entityManager = $entityManager;
        $this->estadoRepository = $estadoRepository;
    }

    #[Route('/', name: 'estado_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('estado/index.html.twig', [
            'estados' => $this->estadoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'estado_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $estado = new Estado();
        $form = $this->createForm(EstadoType::class, $estado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($estado);
            $this->entityManager->flush();
            return $this->redirectToRoute('estado_index');
        }

        return $this->render('estado/new.html.twig', [
            'estado' => $estado,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'estado_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Estado $estado): Response
    {
        $form = $this->createForm(EstadoType::class, $estado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('estado_index');
        }

        return $this->render('estado/edit.html.twig', [
            'estado' => $estado,
            'form' => $form,
        ]);
    }
}