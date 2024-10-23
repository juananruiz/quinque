<?php
// src/Form/QuinquenioMeritoType.php

namespace App\Form;

use App\Entity\QuinquenioMerito;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuinquenioMeritoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('organismo', TextType::class)
            ->add('categoriaId', IntegerType::class)
            ->add('fechaInicio', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('fechaFin', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('estado', IntegerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuinquenioMerito::class,
        ]);
    }
}