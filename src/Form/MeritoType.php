<?php
// src/Form/MeritoType.php

namespace App\Form;

use App\Entity\Merito;
use App\Entity\Categoria;
use App\Entity\Estado;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeritoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('organismo', TextType::class, [
                'label' => 'Organismo',
                'attr' => ['class' => 'form-control']
            ])
            ->add('categoria', EntityType::class, [ 
                'class' => Categoria::class,
                'choice_label' => 'nombre',
                'placeholder' => 'Seleccione una categoría',
                'required' => true,
                'label' => 'Categoría',
                'attr' => ['class' => 'form-control']
            ])
            ->add('fechaInicio', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Fecha Inicio',
                'attr' => ['class' => 'form-control']
            ])
            ->add('fechaFin', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Fecha Fin',
                'attr' => ['class' => 'form-control']
            ])
            ->add('estado', EntityType::class, [ // Aseguramos que estado también use EntityType
                'class' => Estado::class,
                'choice_label' => 'nombre',
                'placeholder' => 'Seleccione un estado',
                'required' => true,
                'label' => 'Estado',
                'attr' => ['class' => 'form-control']
            ])
            // Otros campos...
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Merito::class,
        ]);
    }
}