<?php

namespace App\Form;

use App\Entity\PropertySearch;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertySearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numAppart', IntegerType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => "Numéro d'appartement"
                ]
            ])
            ->add('residence', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Toutes' => 0,
                    'Oxygène' => 1,
                    'Avenir' => 2,
                    'La renaissance' => 3,
                    'Théo' => 4,
                    'Evasion' => 5,
                ],


            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PropertySearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix(){
        return '';
    }
}
