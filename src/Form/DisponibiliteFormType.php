<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class DisponibiliteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $roles = ['role1', 'role2', 'role3'];

        $builder
            ->add('Nom')
            ->add('Email', EmailType::class)
            ->add('Texte', TextareaType::class, array(
                'label' => 'Field',
                'empty_data' => 'Default value'
            ))
            ->add('Arrhes', CheckboxType::class, [
                'label'    => 'Arrhes de réservation ?',
                'required' => false,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
