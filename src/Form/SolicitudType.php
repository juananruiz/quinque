<?php

namespace App\Form;

use App\Entity\Solicitud;
use App\Entity\Convocatoria;
use App\Repository\ConvocatoriaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SolicitudType
 */
class SolicitudType extends AbstractType
{
    private ConvocatoriaRepository $convocatoriaRepository;

    /**
     * Constructor.
     *
     * @param ConvocatoriaRepository $convocatoriaRepository 
     */
    public function __construct(ConvocatoriaRepository $convocatoriaRepository)
    {
        $this->convocatoriaRepository = $convocatoriaRepository;
    }

    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder The form builder.
     * @param array                $options The options.
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $activeConvocatoria = $this->convocatoriaRepository->findOneBy(['activa' => 1]);

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
                    'attr' => ['class' => 'form-control']
                ]
            )
            ->add(
                'fechaEntrada',
                DateType::class,
                [
                    'widget' => 'single_text',
                    'attr' => ['class' => 'form-control'],
                    'label' => 'Fecha entrada'
                ]
            )
            ->add(
                'personaId',
                IntegerType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                        'style' => 'display:none;'
                    ],
                    'label' => false
                ]
            )
            ->add(
                'save',
                SubmitType::class,
                [
                    'label' => 'Crear Solicitud',
                ]
            );
    }

    /**
     * Configures the options for this type.
     * 
     * @param OptionsResolver $resolver The resolver for the options.
     * 
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Solicitud::class,
            ]
        );
    }
}
