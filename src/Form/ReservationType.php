<?php

namespace App\Form;

use App\Entity\Locataire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('statut')
//            ->add('loyer')
//            ->add('reduction')
//            ->add('caution')
            ->add('dateArivee', DateType::class, [
                  'label'=>"Date d'arivée",
                  'widget'=>'single_text',
                  'required'=>true,
                ])
//            ->add('dateDepart')
//            ->add('dateResiliation')
            ->add('civilite', ChoiceType::class, [
                    'choices' => [
                        'Mr' => 1,
                        'Mme' => 2,
                        'Mlle' => 3,
                    ],
                ])
            ->add('dateNaissancce', DateType::class, [
                  'label'=>"Date de naissance",
                  'widget'=>'single_text',
                  'required'=>true,
                ])
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('ville')
            ->add('codePostal')
            ->add('telephoneMobile')
//            ->add('telephoneFixe')
//            ->add('etablissement')
            ->add('immatriculationVehicule')
//            ->add('iban')
//            ->add('codeSWIFT')
//            ->add('nomCautionneur')
//            ->add('prenomCautionneur')
//            ->add('adresseCautionneur')
//            ->add('villeCautionneur')
//            ->add('codePostalCautionneur')
//            ->add('telephoneCautionneur')
            ->add('email')
            ->add('revenuImposable')
//            ->add('age')
//            ->add('appartements')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Locataire::class,
        ]);
    }
}
