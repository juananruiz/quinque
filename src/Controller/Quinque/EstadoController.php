<?php

/**
 * src/Controller/Quinque/EstadoController.php.
 *
 * @author Juanan Ruiz <juanan@us.es>
 *
 */

namespace App\Controller\Quinque;

use App\Entity\Quinque\Estado;
use App\Form\Quinque\EstadoType;
use App\Repository\Quinque\EstadoRepository;
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
        EstadoRepository $estadoRepository,
    ) {
        $this->entityManager = $entityManager;
        $this->estadoRepository = $estadoRepository;
    }

    #[Route('/', name: 'quinque_estado_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('intranet/quinque/admin/estado/index.html.twig', [
            'estados' => $this->estadoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'quinque_estado_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $estado = new Estado();
        $form = $this->createForm(EstadoType::class, $estado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($estado);
            $this->entityManager->flush();

            return $this->redirectToRoute('quinque_estado_index');
        }

        return $this->render('intranet/quinque/admin/estado/new.html.twig', [
            'estado' => $estado,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'quinque_estado_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Estado $estado): Response
    {
        $form = $this->createForm(EstadoType::class, $estado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('quinque_estado_index');
        }

        return $this->render('intranet/quinque/admin/estado/edit.html.twig', [
            'estado' => $estado,
            'form' => $form,
        ]);
    }
}
