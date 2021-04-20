<?php

namespace App\Form;

use App\Entity\ContactHistorique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactHistoriqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class, array(
                'attr' => array(
                    'placeholder' => ('Entrez votre nom'),
                )))
            ->add('prenom',TextType::class, array(
                'attr' => array(
                    'placeholder' => ('Entrez votre prénom'),
                )))
            ->add('contFrom', emailType::class, array(
                'attr' => array(
                    'placeholder' => ('Entrez votre e-mail'),
                )))
            ->add('message', TextareaType::class, array(
                'attr' => array(
                    'placeholder' => ('Entrez votre message'),
                )))
            ->add('tel', TextType::class, array(
                'attr' => array(
                    'placeholder' => ('Entrez votre numéro de télphone'),
                )));
//            ->add('adresse')
//            ->add('cp')
//            ->add('ville')
//            ->add('siteUid')
//            ->add('siteLang')
//            ->add('date')
//            ->add('sujet')
//            ->add('societe');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContactHistorique::class,
        ]);
    }
}
