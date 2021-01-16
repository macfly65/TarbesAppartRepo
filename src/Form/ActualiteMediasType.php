<?php

namespace App\Form;

use App\Entity\ActualiteMedias;
use App\Entity\ActualiteCategs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class ActualiteMediasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomFichier', FileType::class, [
                    'data_class' => null,
                    'required' => false,
                    'multiple' => true
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ActualiteMedias::class,
        ]);
    }
}
