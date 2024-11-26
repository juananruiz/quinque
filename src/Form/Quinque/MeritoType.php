<?php

// src/Form/MeritoType.php

namespace App\Form\Quinque;

use App\Entity\Quinque\Categoria;
use App\Entity\Quinque\Merito;
use App\Entity\Quinque\MeritoEstado;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeritoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('organismo', TextType::class, [
                'label' => 'Organismo',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('categoria', EntityType::class, [
                'class' => Categoria::class,
                'choice_label' => 'nombre',
                'placeholder' => 'Seleccione una categoría',
                'required' => true,
                'label' => 'Categoría',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('fechaInicio', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Fecha Inicio',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('fechaFin', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Fecha Fin',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('estado', EntityType::class, [
                'class' => MeritoEstado::class,
                'choice_label' => 'nombre',
                'placeholder' => 'Seleccione un estado',
                'required' => true,
                'label' => 'Estado',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dedicacion', TextType::class, [
                'label' => 'Dedicación',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('observaciones', TextType::class, [
                'label' => 'Observaciones',
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Merito::class,
        ]);
    }
}
