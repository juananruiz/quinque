<?php

namespace App\Form;

use App\Entity\Persona;
use App\Entity\Departamento;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('apellidos')
            ->add('telefono')
            ->add('fechaNacimiento', null, [
                'widget' => 'single_text',
            ])
            ->add('dni')
            ->add('departamento', EntityType::class, [
                'class' => Departamento::class,
                'choice_label' => 'nombre',
                'placeholder' => 'Seleccione un departamento',
                'required' => true,
                'label' => 'Departamento',
                'attr' => [
                    'class' => 'form-control'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Persona::class,
        ]);
    }
}
