<?php

namespace App\Form;

use App\Entity\Solicitud;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SolicitudType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'fechaEntrada', DateType::class, 
                [
                    'widget' => 'single_text',
                    'attr' => ['class' => 'form-control'],
                    'label' => 'Fecha entrada'
                ]
            )
            ->add(
                'personaId', IntegerType::class, 
                [
                    'attr' => ['class' => 'form-control']
                ]
            )
            ->add(
                'save', SubmitType::class, 
                [
                    'label' => 'Crear Solicitud',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Solicitud::class,
        ]);
    }
}
