<?php

namespace App\Form\Quinque;

use App\Entity\Quinque\Convocatoria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConvocatoriaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('fechaInicioSolicitud')
            ->add('fechaFinSolicitud')
            ->add('fechaResolucion')
            ->add('delegaFirmaFecha')
            ->add('delegaFirmaPersona')
            ->add('delegaFirmaCargo')
            ->add('activa', ChoiceType::class, [
                'label' => 'Estado',
                'choices' => [
                    'Inactiva' => 0,
                    'Activa' => 1,
                ],
                'expanded' => false,
                'multiple' => false,
                'attr' => ['class' => 'form-control'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Convocatoria::class,
        ]);
    }
}
