<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class QuittanceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numAppart')
            ->add('dateDebutQuitance',DateType::class, [
                'label'=>"Date debut quittance",
                'widget'=>'single_text',
                'required'=>true,
            ])
            ->add('dateFinQuitance',DateType::class, [
                'label'=>"Date fin quittance",
                'widget'=>'single_text',
                'required'=>true,
            ])
            ->add('datePaiement',DateType::class, [
                'label'=>"Date paiement",
                'widget'=>'single_text',
                'required'=>true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
