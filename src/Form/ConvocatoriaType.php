<?php

namespace App\Form;

use App\Entity\Convocatoria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConvocatoriaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('fechaInicioSolicitud', null, [
                'widget' => 'single_text',
            ])
            ->add('fechaFinSolicitud', null, [
                'widget' => 'single_text',
            ])
			->add('activa', null, [
				'label' => 'Activa',
				'required' => true,
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
