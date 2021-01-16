<?php

namespace App\Form;

use App\Entity\ContactHistorique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactHistoriqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('contFrom')
            ->add('message')
            ->add('tel')
//            ->add('adresse')
//            ->add('cp')
//            ->add('ville')
//            ->add('siteUid')
//            ->add('siteLang')
//            ->add('date')
//            ->add('sujet')
//            ->add('societe')
                
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContactHistorique::class,
        ]);
    }
}
