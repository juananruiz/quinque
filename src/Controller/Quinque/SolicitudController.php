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
use App\Repository\Quinque\MeritoEstadoRepository;
use App\Repository\Quinque\PersonaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('intranet/quinque/admin/solicitud', name: 'intranet_quinque_admin_solicitud_')]
/**
 * Controller for handling Solicitud related actions.
 */
final class SolicitudController extends AbstractController
{
    private CategoriaRepository $_categoriaRepository;
    private MeritoEstadoRepository $_meritoEstadoRepository;
    private PersonaRepository $_personaRepository;
    private EntityManagerInterface $_entityManager;

    /**
     * Constructor for SolicitudController.
     *
     * @param CategoriaRepository    $categoriaRepository The categoria repository
     * @param MeritoEstadoRepository $meritoEstadoRepository The merito estado repository
     * @param PersonaRepository      $personaRepository   The persona repository
     * @param EntityManagerInterface $entityManager       The entity manager
     */
    public function __construct(
        CategoriaRepository $categoriaRepository,
        MeritoEstadoRepository $meritoEstadoRepository,
        PersonaRepository $personaRepository,
        EntityManagerInterface $entityManager,
    ) {
        $this->_categoriaRepository = $categoriaRepository;
        $this->_meritoEstadoRepository = $meritoEstadoRepository;
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
    #[Route('/{id}', name: 'show')]
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
                'estados' => $this->_meritoEstadoRepository->findAll(),
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
    #[Route('/new/{personaId}', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, int $personaId): Response
    {
        $persona = $this->_personaRepository->find($personaId);
        if (!$persona) {
            throw $this->createNotFoundException('Persona no encontrada');
        }

        $solicitud = new Solicitud();
        $solicitud->setPersona($persona);
        
        // Establecer el estado inicial (ID = 1)
        $estadoInicial = $this->_entityManager->getReference('App\Entity\Quinque\SolicitudEstado', 1);
        $solicitud->setEstado($estadoInicial);
        
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

    /**
     * Edits an existing Solicitud entity.
     *
     * @param Request   $request   The HTTP request
     * @param Solicitud $solicitud The Solicitud entity to edit
     *
     * @return Response The response object
     */
    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Solicitud $solicitud): Response
    {
        $form = $this->createForm(SolicitudType::class, $solicitud);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->_entityManager->flush();

            return $this->redirectToRoute(
                'intranet_quinque_admin_solicitud_show',
                ['id' => $solicitud->getId()]
            );
        }

        return $this->render(
            'intranet/quinque/admin/solicitud/edit.html.twig',
            [
                'solicitud' => $solicitud,
                'form' => $form->createView(),
            ]
        );
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Solicitud $solicitud, EntityManagerInterface $entityManager): Response
    {
        // No permitir si la persona tienes solicitudes asociadas,
        // primero habría que borrar aquellas
        if ($solicitud->getMeritos()->count() > 0) {
            $this->addFlash('error', 'No se puede eliminar una solicitud con meritos asociadas');

            return $this->redirectToRoute(
                'intranet_quinque_admin_solicitud_show', 
                ['id' => $solicitud->getId()], Response::HTTP_SEE_OTHER
            );
        }

        if ($this->isCsrfTokenValid(
            'delete'.$solicitud->getId(),
            $request->get('_token')
        )
        ) {
            $entityManager->remove($solicitud);
            $entityManager->flush();
        }

        return $this->redirectToRoute(
            'intranet_quinque_admin_persona_show',
            [
                'id' => $solicitud->getPersona()->getId(),
            ], 
            Response::HTTP_SEE_OTHER);
    }

    /**
     * Generates a PDF document for a Solicitud entity.
     *
     * @param Solicitud $solicitud The Solicitud entity
     *
     * @return Response The PDF response
     */
    #[Route('/{id}/resolucion-pdf', name: 'resolucion_pdf')]
    public function generateResolucionPdf(Solicitud $solicitud): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        // Convert image to base64
        $imagePath = $this->getParameter('kernel.project_dir') . '/public/assets/images/sello-personal-docente.png';
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageSrc = 'data:image/png;base64,' . $imageData;
        
        if ($solicitud->getMeritosComputados() >= 1825) { // TODO: Evitar número mágico
            $solicitud->setEstado($this->_entityManager->getReference('App\Entity\Quinque\SolicitudEstado', 2));
            $this->_entityManager->flush();
            $html = $this->renderView('intranet/quinque/admin/solicitud/pdf_estimado.html.twig', 
                [
                'solicitud' => $solicitud,
                'meritosComputados' => $solicitud->getMeritosComputados(),
                'selloBase64' => $imageSrc,
                ]);
        } else {
            $solicitud->setEstado($this->_entityManager->getReference('App\Entity\Quinque\SolicitudEstado', 3));
            $this->_entityManager->flush();
            //return $this->redirectToRoute('quinque_solicitud_show', ['id' => $solicitud->getId()], Response::HTTP_SEE_OTHER);
            $html = $this->renderView('intranet/quinque/admin/solicitud/pdf_desestimado.html.twig', 
                [
                'solicitud' => $solicitud,
                'meritosComputados' => $solicitud->getMeritosComputados(),
                'selloBase64' => $imageSrc,
                ]);
        }
        
        $dompdf->loadHtml($html);
        
        // Configure page settings
        $dompdf->setPaper('A4', 'portrait');
        
        // Render the HTML as PDF
        $dompdf->render();
        
        // Output the generated PDF to Browser (inline view)
        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'inline; filename="solicitud-' . $solicitud->getId() . '.pdf"');
        
        return $response;
    }
}
