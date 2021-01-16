<?php

namespace App\Form;

use App\Entity\Actualite;
use App\Entity\ActualiteCategs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class ActualiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date')
            ->add('titre')
            ->add('texte')
            ->add('isValid')
            ->add('categorie', EntityType::class, [
                    'class' => ActualiteCategs::class,
                    'choice_label' => 'nom'
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Actualite::class,
        ]);
    }
}
