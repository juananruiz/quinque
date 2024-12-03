<?php

namespace App\Form\Peticion;

use App\Entity\Peticion\Solicitud;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SolicitudType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('asunto', TextType::class, [
                'label' => 'Asunto',
                'attr' => [
                    'placeholder' => 'Escriba el asunto de la solicitud',
                    'class' => 'form-control'
                ]
            ])
            ->add('contenido', TextareaType::class, [
                'label' => 'Contenido',
                'attr' => [
                    'placeholder' => 'Escriba el contenido de la solicitud',
                    'class' => 'form-control',
                    'rows' => 5
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Solicitud::class,
        ]);
    }
}
