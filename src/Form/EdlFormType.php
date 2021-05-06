<?php

namespace App\Form;

use App\Entity\Edl;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class EdlFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('entree_sonette_interphone', HiddenType::class)
            ->add('entree_sonette_interphone_com')
            ->add('entree_porte_serrurerie', HiddenType::class)
            ->add('entree_porte_serrurerie_com')
            ->add('entree_plafond', HiddenType::class)
            ->add('entree_plafond_com')
            ->add('entree_revetements_muraux', HiddenType::class)
            ->add('entree_revetements_muraux_com')
            ->add('entree_plinthes', HiddenType::class)
            ->add('entree_plinthes_com')
            ->add('entree_sol', HiddenType::class)
            ->add('entree_sol_com')
            ->add('entree_luminaire', HiddenType::class)
            ->add('entree_luminaire_com')
            ->add('entree_interupt_prise', HiddenType::class)
            ->add('entree_interupt_prise_com')
            ->add('entree_placard', HiddenType::class)
            ->add('entree_placard_com')
            ->add('entree_fenetre', HiddenType::class)
            ->add('entree_fenetre_com')
            ->add('entree_volet', HiddenType::class)
            ->add('entree_volet_com')
            ->add('sdb_porte_serrurerie', HiddenType::class)
            ->add('sdb_porte_serrurerie_com')
            ->add('sdb_plafond', HiddenType::class)
            ->add('sdb_plafond_com')
            ->add('sdb_revetements_muraux', HiddenType::class)
            ->add('sdb_revetements_muraux_com')
            ->add('sdb_plinthes', HiddenType::class)
            ->add('sdb_plinthes_com')
            ->add('sdb_sol', HiddenType::class)
            ->add('sdb_sol_com')
            ->add('sdb_luminaire', HiddenType::class)
            ->add('sdb_luminaire_com')
            ->add('sdb_interupt_prise', HiddenType::class)
            ->add('sdb_interupt_prise_com')
            ->add('sdb_radiateur', HiddenType::class)
            ->add('sdb_radiateur_com')
            ->add('sdb_placard', HiddenType::class)
            ->add('sdb_placard_com')
            ->add('sdb_fenetre', HiddenType::class)
            ->add('sdb_fenetre_com')
            ->add('sdb_lavabo', HiddenType::class)
            ->add('sdb_lavabo_com')
            ->add('sdb_robinetterie_lavabo', HiddenType::class)
            ->add('sdb_robinetterie_lavabo_com')
            ->add('sdb_douche', HiddenType::class)
            ->add('sdb_douche_com')
            ->add('sdb_robinetterie_douche', HiddenType::class)
            ->add('sdb_robinetterie_douche_com')
            ->add('sdb_paroi_douche', HiddenType::class)
            ->add('sdb_paroi_douche_com')
            ->add('sdb_baignoire', HiddenType::class)
            ->add('sdb_baignoire_com')
            ->add('sdb_robinetterie_baignoire', HiddenType::class)
            ->add('sdb_robinetterie_baignoire_com')
            ->add('sdb_faience', HiddenType::class)
            ->add('sdb_faience_com')
            ->add('sdb_joints', HiddenType::class)
            ->add('sdb_joints_com')
            ->add('wc_porte_serrurerie', HiddenType::class)
            ->add('wc_porte_serrurerie_com')
            ->add('wc_plafond', HiddenType::class)
            ->add('wc_plafond_com')
            ->add('wc_revetement_muraux', HiddenType::class)
            ->add('wc_revetement_muraux_com')
            ->add('wc_plinthes', HiddenType::class)
            ->add('wc_plinthes_com')
            ->add('wc_sol', HiddenType::class)
            ->add('wc_sol_com')
            ->add('wc_luminaire', HiddenType::class)
            ->add('wc_luminaire_com')
            ->add('wc_interupt_prise', HiddenType::class)
            ->add('wc_interupt_prise_com')
            ->add('wc_cuvette_mecanisme', HiddenType::class)
            ->add('wc_cuvette_mecanisme_com')
            ->add('wc_abattant', HiddenType::class)
            ->add('wc_abattant_com')
            ->add('wc_fenetre', HiddenType::class)
            ->add('wc_fenetre_com')
            ->add('ch1_porte_serrurerie', HiddenType::class)
            ->add('ch1_porte_serrurerie_com')
            ->add('ch1_plafond', HiddenType::class)
            ->add('ch1_plafond_com')
            ->add('ch1_revetements_muraux', HiddenType::class)
            ->add('ch1_revetements_muraux_com')
            ->add('ch1_plinthes', HiddenType::class)
            ->add('ch1_plinthes_com')
            ->add('ch1_sol', HiddenType::class)
            ->add('ch1_sol_com')
            ->add('ch1_luminaire', HiddenType::class)
            ->add('ch1_luminaire_com')
            ->add('ch1_interupt_prise', HiddenType::class)
            ->add('ch1_interupt_prise_com')
            ->add('ch1_radiateur', HiddenType::class)
            ->add('ch1_radiateur_com')
            ->add('ch1_placard', HiddenType::class)
            ->add('ch1_placard_com')
            ->add('ch1_fenetre', HiddenType::class)
            ->add('ch1_fenetre_com')
            ->add('ch1_volet', HiddenType::class)
            ->add('ch1_volet_com')
            ->add('ch2_porte_serrurerie', HiddenType::class)
            ->add('ch2_porte_serrurerie_com')
            ->add('ch2_plafond', HiddenType::class)
            ->add('ch2_plafond_com')
            ->add('ch2_revetements_muraux', HiddenType::class)
            ->add('ch2_revetements_muraux_com')
            ->add('ch2_plinthes', HiddenType::class)
            ->add('ch2_plinthes_com')
            ->add('ch2_sol', HiddenType::class)
            ->add('ch2_sol_com')
            ->add('ch2_luminaire', HiddenType::class)
            ->add('ch2_luminaire_com')
            ->add('ch2_interupt_prise', HiddenType::class)
            ->add('ch2_interupt_prise_com')
            ->add('ch2_radiateur', HiddenType::class)
            ->add('ch2_radiateur_com')
            ->add('ch2_placard', HiddenType::class)
            ->add('ch2_placard_com')
            ->add('ch2_fenetre', HiddenType::class)
            ->add('ch2_fenetre_com')
            ->add('ch2_volet', HiddenType::class)
            ->add('ch2_volet_com')
            ->add('ch3_porte_serrurerie', HiddenType::class)
            ->add('ch3_porte_serrurerie_com')
            ->add('ch3_plafond', HiddenType::class)
            ->add('ch3_plafond_com')
            ->add('ch3_revetements_muraux', HiddenType::class)
            ->add('ch3_revetements_muraux_com')
            ->add('ch3_plinthes', HiddenType::class)
            ->add('ch3_plinthes_com')
            ->add('ch3_sol', HiddenType::class)
            ->add('ch3_sol_com')
            ->add('ch3_luminaire', HiddenType::class)
            ->add('ch3_luminaire_com')
            ->add('ch3_interupt_prise', HiddenType::class)
            ->add('ch3_interupt_prise_com')
            ->add('ch3_radiateur', HiddenType::class)
            ->add('ch3_radiateur_com')
            ->add('ch3_placard', HiddenType::class)
            ->add('ch3_placard_com')
            ->add('ch3_fenetre', HiddenType::class)
            ->add('ch3_fenetre_com')
            ->add('ch3_volet', HiddenType::class)
            ->add('ch3_volet_com')
            ->add('sejour_plafond', HiddenType::class)
            ->add('sejour_plafond_com')
            ->add('sejour_revetement_muraux', HiddenType::class)
            ->add('sejour_revetement_muraux_com')
            ->add('sejour_plinthes', HiddenType::class)
            ->add('sejour_plinthes_com')
            ->add('sejour_sol', HiddenType::class)
            ->add('sejour_sol_com')
            ->add('sejour_luminaire', HiddenType::class)
            ->add('sejour_luminaire_com')
            ->add('sejour_interupt_prise', HiddenType::class)
            ->add('sejour_interupt_prise_com')
            ->add('sejour_fenetre', HiddenType::class)
            ->add('sejour_fenetre_com')
            ->add('sejour_volet', HiddenType::class)
            ->add('sejour_faience', HiddenType::class)
            ->add('sejour_faience_com')
            ->add('sejour_paillasse', HiddenType::class)
            ->add('sejour_paillasse_com')
            ->add('sejour_evier', HiddenType::class)
            ->add('sejour_evier_com')
            ->add('sejour_robinetterie', HiddenType::class)
            ->add('sejour_robinetterie_com')
            ->add('sejour_vmc', HiddenType::class)
            ->add('sejour_vmc_com')
            ->add('sejour_table_cuisson', HiddenType::class)
            ->add('sejour_table_cuisson_com')
            ->add('sejour_frigo', HiddenType::class)
            ->add('sejour_frigo_com')
            ->add('sejour_hotte', HiddenType::class)
            ->add('sejour_hotte_com')
            ->add('sejour_radiateur', HiddenType::class)
            ->add('sejour_radiateur_com')
            ->add('sejour_meuble_cuisine_bas', HiddenType::class)
            ->add('sejour_meuble_cuisine_bas_com')
            ->add('sejour_meuble_cuisine_haut', HiddenType::class)
            ->add('sejour_meuble_cuisine_haut_com')
            ->add('sejour_volet_com')
            ->add('couloir_porte_serrurerie', HiddenType::class)
            ->add('couloir_porte_serrurerie_com')
            ->add('couloir_plafond', HiddenType::class)
            ->add('couloir_plafond_com')
            ->add('couloir_revetement_muraux', HiddenType::class)
            ->add('couloir_revetement_muraux_com')
            ->add('couloir_plinthes', HiddenType::class)
            ->add('couloir_plinthes_com')
            ->add('couloir_sol', HiddenType::class)
            ->add('couloir_sol_com')
            ->add('couloir_luminaire', HiddenType::class)
            ->add('couloir_luminaire_com')
            ->add('couloir_interupt_prise', HiddenType::class)
            ->add('couloir_interupt_prise_com')
            ->add('couloir_fenetre', HiddenType::class)
            ->add('couloir_fenetre_com')
            ->add('couloir_volet', HiddenType::class)
            ->add('couloir_volet_com')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Edl::class,
        ]);
    }
}
