<?php

namespace App\Form\Quinque;

use App\Entity\Quinque\Convocatoria;
use App\Entity\Quinque\Solicitud;
use App\Entity\Quinque\SolicitudEstado;
use App\Repository\Quinque\ConvocatoriaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SolicitudType.
 */
class SolicitudType extends AbstractType
{
    private ConvocatoriaRepository $convocatoriaRepository;

    /**
     * Constructor.
     */
    public function __construct(ConvocatoriaRepository $convocatoriaRepository)
    {
        $this->convocatoriaRepository = $convocatoriaRepository;
    }

    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder the form builder
     * @param array                $options the options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $activeConvocatoria = $this->convocatoriaRepository->findOneBy(['activa' => 1]);
        $solicitud = $builder->getData();
        $isEdit = $solicitud && $solicitud->getId() !== null;

        $builder
            ->add(
                'convocatoria',
                EntityType::class,
                [
                    'class' => Convocatoria::class,
                    'choice_label' => 'nombre',
                    'data' => $activeConvocatoria,
                    'required' => true,
                    'label' => 'Convocatoria',
                    'attr' => ['class' => 'form-control'],
                ]
            )
            ->add(
                'fechaEntrada',
                DateType::class,
                [
                    'widget' => 'single_text',
                    'required' => true,
                    'label' => 'Fecha entrada',
                    'attr' => ['class' => 'form-control'],
                ]
            )
            ->add(
                'personaId',
                IntegerType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                        'style' => 'display:none;',
                    ],
                    'label' => false,
                ]
            );

        // Solo mostrar el campo estado en el formulario de edición
        if ($isEdit) {
            $builder->add(
                'estado',
                EntityType::class,
                [
                    'class' => SolicitudEstado::class,
                    'choice_label' => 'nombre',
                    'required' => false,
                ]
            );
        }
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver the resolver for the options
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Solicitud::class,
            ]
        );
    }
}
