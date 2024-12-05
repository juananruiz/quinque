<?php

namespace App\Form\Peticion;

use App\Entity\Peticion\Solicitud;
use App\Entity\Persona;
use App\Entity\Unidad;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('solicitante', EntityType::class, [
                'class' => Persona::class,
                'choice_label' => function(Persona $persona) {
                    return $persona->getNombre() . ' ' . $persona->getApellidos();
                },
                'placeholder' => 'Seleccione un solicitante',
                'required' => true,
                'attr' => [
                    'class' => 'form-control select2',
                    'data-placeholder' => 'Buscar solicitante...'
                ]
            ])
            ->add('asignado', EntityType::class, [
                'class' => Persona::class,
                'choice_label' => function(Persona $persona) {
                    return $persona->getNombre() . ' ' . $persona->getApellidos();
                },
                'placeholder' => 'Asigne la solicitud a un gestor (opcional)',
                'required' => false,
                'attr' => [
                    'class' => 'form-control select2',
                    'data-placeholder' => 'Buscar gestor...'
                ]
            ])
            ->add('unidad', EntityType::class, [
                'class' => Unidad::class,
                'choice_label' => function(Unidad $unidad) {
                    return $unidad->getNombre();
                },
                'placeholder' => 'Seleccione una unidad (opcional)',
                'required' => false,
                'attr' => [
                    'class' => 'form-control select2',
                    'data-placeholder' => 'Buscar unidad...'
                ]
            ])
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
