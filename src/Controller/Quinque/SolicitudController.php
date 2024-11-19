<?php

/**
 * Controlador para las rutas de las solicitudes.
 *
 * @path: Src/Controller/Quinque/SolicitudController.php.
 *
 * @author: Juanan Ruiz <juanan@us.es>
 */

namespace App\Controller\Quinque;

use App\Entity\Quinque\Merito;
use App\Entity\Quinque\Solicitud;
use App\Form\Quinque\MeritoType;
use App\Form\Quinque\SolicitudType;
use App\Repository\Quinque\CategoriaRepository;
use App\Repository\Quinque\EstadoRepository;
use App\Repository\Quinque\PersonaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/solicitud')]
/**
 * Controller for handling Solicitud related actions.
 */
final class SolicitudController extends AbstractController
{
    private CategoriaRepository $_categoriaRepository;
    private EstadoRepository $_estadoRepository;
    private $_personaRepository;
    private $_entityManager;

    /**
     * Constructor for SolicitudController.
     *
     * @param CategoriaRepository    $categoriaRepository The categoria repository
     * @param EstadoRepository       $estadoRepository    The estado repository
     * @param PersonaRepository      $personaRepository   The persona repository
     * @param EntityManagerInterface $entityManager       The entity manager
     */
    public function __construct(
        CategoriaRepository $categoriaRepository,
        EstadoRepository $estadoRepository,
        PersonaRepository $personaRepository,
        EntityManagerInterface $entityManager,
    ) {
        $this->_categoriaRepository = $categoriaRepository;
        $this->_estadoRepository = $estadoRepository;
        $this->_personaRepository = $personaRepository;
        $this->_entityManager = $entityManager;
    }

    /**
     * Displays a Solicitud entity.
     *
     * @param Solicitud $solicitud The Solicitud entity
     *
     * @return Response The response object
     */
    #[Route('/{id}', name: 'quinque_solicitud_show')]
    public function show(Solicitud $solicitud): Response
    {
        $merito = new Merito();
        $form = $this->createForm(MeritoType::class, $merito);

        return $this->render(
            'intranet/quinque/admin/solicitud/show.html.twig',
            [
                'solicitud' => $solicitud,
                'meritos' => $solicitud->getMeritos(),
                'categorias' => $this->_categoriaRepository->findAll(),
                'estados' => $this->_estadoRepository->findAll(),
                'persona' => $solicitud->getPersona(),
                'meritosComputados' => $solicitud->getMeritosComputados(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Creates a new Solicitud entity.
     *
     * @param Request $request   The HTTP request
     * @param int     $personaId The ID of the persona
     *
     * @return Response The response object
     */
    #[Route('/new/{personaId}', name: 'quinque_solicitud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, int $personaId): Response
    {
        $persona = $this->_personaRepository->find($personaId);
        if (!$persona) {
            throw $this->createNotFoundException('Persona no encontrada');
        }

        $solicitud = new Solicitud();
        $solicitud->setPersona($persona);
        $form = $this->createForm(SolicitudType::class, $solicitud);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->_entityManager->persist($solicitud);
            $this->_entityManager->flush();

            return $this->redirectToRoute(
                'quinque_solicitud_show',
                ['id' => $solicitud->getId()]
            );
        }

        return $this->render(
            'intranet/quinque/admin/solicitud/new.html.twig',
            [
                'solicitud' => $solicitud,
                'form' => $form->createView(),
            ]
        );
    }
}
