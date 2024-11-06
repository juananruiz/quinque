<?php

namespace App\Controller;

use App\Entity\Convocatoria;
use App\Form\ConvocatoriaType;
use App\Repository\ConvocatoriaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/convocatoria')]
class ConvocatoriaController extends AbstractController
{
	#[Route('/', name: 'convocatoria_index', methods: ['GET'])]
	public function index(ConvocatoriaRepository $convocatoriaRepository): Response
	{
		return $this->render('convocatoria/index.html.twig', [
			'convocatorias' => $convocatoriaRepository->findAll(),
		]);
	}

	#[Route('/new', name: 'convocatoria_new', methods: ['GET', 'POST'])]
	public function new(Request $request, EntityManagerInterface $entityManager): Response
	{
		$convocatoria = new Convocatoria();
		$form = $this->createForm(ConvocatoriaType::class, $convocatoria);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager->persist($convocatoria);
			$entityManager->flush();

			return $this->redirectToRoute('convocatoria_index');
		}

		return $this->render('convocatoria/new.html.twig', [
			'convocatoria' => $convocatoria,
			'form' => $form,
		]);
	}

	#[Route('/{id}/show', name: 'convocatoria_show', methods: ['GET'])]
	public function show(Convocatoria $convocatoria): Response
	{
		return $this->render('convocatoria/show.html.twig', [
			'convocatoria' => $convocatoria,
		]);
	}

	#[Route('/{id}/edit', name: 'convocatoria_edit', methods: ['GET', 'POST'])]
	public function edit(Request $request, Convocatoria $convocatoria, EntityManagerInterface $entityManager): Response
	{
		$form = $this->createForm(ConvocatoriaType::class, $convocatoria);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager->flush();

			return $this->redirectToRoute('convocatoria_index');
		}

		return $this->render('convocatoria/edit.html.twig', [
			'convocatoria' => $convocatoria,
			'form' => $form,
		]);
	}

	#[Route('/{id}/delete', name: 'convocatoria_delete', methods: ['POST'])]
	public function delete(Request $request, Convocatoria $convocatoria, EntityManagerInterface $entityManager): Response
	{
		if ($this->isCsrfTokenValid('delete' . $convocatoria->getId(), $request->request->get('_token'))) {
			$entityManager->remove($convocatoria);
			$entityManager->flush();
		}

		return $this->redirectToRoute('convocatoria_index');
	}
}
