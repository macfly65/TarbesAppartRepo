<?php

namespace App\Form;

use App\Entity\Appartement;
use App\Entity\prestataire;
use App\Entity\PT;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class PTFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $today =  new \DateTime();

        $builder
            ->add('statut', ChoiceType::class, [
                'choices'  => [
                    'Transféré au prestataire, en attente de lecture' => 1,
                    'Sélection du prestataire en attente' => 2,
                    'Travaux en cours' => 3,
                    'Travaux terminé' => 4,
                ],
            ])
            ->add('comentLocataire')
            ->add('comentGestionaireToLocataire')
            ->add('comentGestionaireToPrestataire')
            ->add('comentPresta')
            ->add('prestataire', EntityType::class, [
                'class' => prestataire::class,
                'choice_label' => 'nom',
                'required' => true
            ])
            ->add('appartement', EntityType::class, [
                'class' => Appartement::class,
                'choice_label' => 'numero'
            ])
            ->add('dateDemande',DateType::class, [
                'label'=>"Date demande",
                'widget'=>'single_text',
                'required'=>true,
                'data'=> $today,
            ])
            ->add('dateLecturePresta',DateType::class, [
                'label'=>"Date lecture prestataire",
                'widget'=>'single_text',
                'required'=>false,
            ])
            ->add('dateFinTravaux',DateType::class, [
                'label'=>"Date de fin des travaux",
                'widget'=>'single_text',
                'required'=>false,
            ])
            ->add('dateValidation',DateType::class, [
                'label'=>"Date de validation",
                'widget'=>'single_text',
                'required'=>false,
            ])
            ->add('dateEnvoiPresta',DateType::class, [
                'label'=>"Date de validation",
                'widget'=>'single_text',
                'required'=>false,

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PT::class,
        ]);
    }
}
