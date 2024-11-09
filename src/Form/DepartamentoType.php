<?php
// src/Form/DepartamentoType.php
namespace App\Form;

use App\Entity\Departamento;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepartamentoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre del Departamento',
                'attr' => ['class' => 'form-control']
            ])
            ->add('codigoUxxi', TextType::class, [
                'label' => 'Código UXXI',
                'attr' => ['class' => 'form-control']
            ])
            ->add('codigoRpt', TextType::class, [
                'label' => 'Código RPT',
                'attr' => ['class' => 'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Departamento::class,
        ]);
    }
}