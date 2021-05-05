<?php

namespace App\Form;

use App\Entity\Edl;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EdlFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('entree_sonette_interphone')
            ->add('entree_sonette_interphone_com')
            ->add('entree_porte_serrurerie')
            ->add('entree_porte_serrurerie_com')
            ->add('entree_plafond')
            ->add('entree_plafond_com')
            ->add('entree_revetements_muraux')
            ->add('entree_revetements_muraux_com')
            ->add('entree_plinthes')
            ->add('entree_plinthes_com')
            ->add('entree_sol')
            ->add('entree_sol_com')
            ->add('entree_luminaire')
            ->add('entree_luminaire_com')
            ->add('entree_interupt_prise')
            ->add('entree_interupt_prise_com')
            ->add('entree_placard')
            ->add('entree_placard_com')
            ->add('entree_fenetre')
            ->add('entree_fenetre_com')
            ->add('entree_volet')
            ->add('entree_volet_com')
            ->add('sdb_porte_serrurerie')
            ->add('sdb_porte_serrurerie_com')
            ->add('sdb_plafond')
            ->add('sdb_plafond_com')
            ->add('sdb_revetements_muraux')
            ->add('sdb_revetements_muraux_com')
            ->add('sdb_plinthes')
            ->add('sdb_plinthes_com')
            ->add('sdb_sol')
            ->add('sdb_sol_com')
            ->add('sdb_luminaire')
            ->add('sdb_luminaire_com')
            ->add('sdb_interupt_prise')
            ->add('sdb_interupt_prise_com')
            ->add('sdb_radiateur')
            ->add('sdb_radiateur_com')
            ->add('sdb_placard')
            ->add('sdb_placard_com')
            ->add('sdb_fenetre')
            ->add('sdb_fenetre_com')
            ->add('sdb_volet')
            ->add('sdb_volet_com')
            ->add('sdb_lavabo')
            ->add('sdb_lavabo_com')
            ->add('sdb_robinetterie_lavabo')
            ->add('sdb_robinetterie_lavabo_com')
            ->add('sdb_douche')
            ->add('sdb_douche_com')
            ->add('sdb_robinetterie_douche')
            ->add('sdb_robinetterie_douche_com')
            ->add('sdb_paroi_douche')
            ->add('sdb_paroi_douche_com')
            ->add('sdb_baignoire')
            ->add('sdb_baignoire_com')
            ->add('sdb_robinetterie_baignoire')
            ->add('sdb_robinetterie_baignoire_com')
            ->add('sdb_faience')
            ->add('sdb_faience_com')
            ->add('sdb_joints')
            ->add('sdb_joints_com')
            ->add('wc_porte_serrurerie')
            ->add('wc_porte_serrurerie_com')
            ->add('wc_plafond')
            ->add('wc_plafond_com')
            ->add('wc_revetement_muraux')
            ->add('wc_revetement_muraux_com')
            ->add('wc_plinthes')
            ->add('wc_plinthes_com')
            ->add('wc_sol')
            ->add('wc_sol_com')
            ->add('wc_luminaire')
            ->add('wc_luminaire_com')
            ->add('wc_interupt_prise')
            ->add('wc_interupt_prise_com')
            ->add('wc_radiateur')
            ->add('wc_radiateur_com')
            ->add('wc_cuvette_mecanisme')
            ->add('wc_cuvette_mecanisme_com')
            ->add('wc_abattant')
            ->add('wc_abattant_com')
            ->add('wc_fenetre')
            ->add('wc_fenetre_com')
            ->add('wc_volet')
            ->add('wc_volet_com')
            ->add('wc_faiences')
            ->add('wc_faiences_com')
            ->add('wc_joints')
            ->add('wc_joints_com')
            ->add('ch1_porte_serrurerie')
            ->add('ch1_porte_serrurerie_com')
            ->add('ch1_plafond')
            ->add('ch1_plafond_com')
            ->add('ch1_revetements_muraux')
            ->add('ch1_revetements_muraux_com')
            ->add('ch1_plinthes')
            ->add('ch1_plinthes_com')
            ->add('ch1_sol')
            ->add('ch1_sol_com')
            ->add('ch1_luminaire')
            ->add('ch1_luminaire_com')
            ->add('ch1_interupt_prise')
            ->add('ch1_interupt_prise_com')
            ->add('ch1_radiateur')
            ->add('ch1_radiateur_com')
            ->add('ch1_placard')
            ->add('ch1_placard_com')
            ->add('ch1_fenetre')
            ->add('ch1_fenetre_com')
            ->add('ch1_volet')
            ->add('ch1_volet_com')
            ->add('ch2_porte_serrurerie')
            ->add('ch2_porte_serrurerie_com')
            ->add('ch2_plafond')
            ->add('ch2_plafond_com')
            ->add('ch2_revetements_muraux')
            ->add('ch2_revetements_muraux_com')
            ->add('ch2_plinthes')
            ->add('ch2_plinthes_com')
            ->add('ch2_sol')
            ->add('ch2_sol_com')
            ->add('ch2_luminaire')
            ->add('ch2_luminaire_com')
            ->add('ch2_interupt_prise')
            ->add('ch2_interupt_prise_com')
            ->add('ch2_radiateur')
            ->add('ch2_radiateur_com')
            ->add('ch2_placard')
            ->add('ch2_placard_com')
            ->add('ch2_fenetre')
            ->add('ch2_fenetre_com')
            ->add('ch2_volet')
            ->add('ch2_volet_com')
            ->add('ch3_porte_serrurerie')
            ->add('ch3_porte_serrurerie_com')
            ->add('ch3_plafond')
            ->add('ch3_plafond_com')
            ->add('ch3_revetements_muraux')
            ->add('ch3_revetements_muraux_com')
            ->add('ch3_plinthes')
            ->add('ch3_plinthes_com')
            ->add('ch3_sol')
            ->add('ch3_sol_com')
            ->add('ch3_luminaire')
            ->add('ch3_luminaire_com')
            ->add('ch3_interupt_prise')
            ->add('ch3_interupt_prise_com')
            ->add('ch3_radiateur')
            ->add('ch3_radiateur_com')
            ->add('ch3_placard')
            ->add('ch3_placard_com')
            ->add('ch3_fenetre')
            ->add('ch3_fenetre_com')
            ->add('ch3_volet')
            ->add('ch3_volet_com')
            ->add('sejour_porte_serrurerie')
            ->add('sejour_porte_serrurerie_com')
            ->add('sejour_plafond')
            ->add('sejour_plafond_com')
            ->add('sejour_revetement_muraux')
            ->add('sejour_revetement_muraux_com')
            ->add('sejour_plinthes')
            ->add('sejour_plinthes_com')
            ->add('sejour_sol')
            ->add('sejour_sol_com')
            ->add('sejour_luminaire')
            ->add('sejour_luminaire_com')
            ->add('sejour_interupt_prise')
            ->add('sejour_interupt_prise_com')
            ->add('sejour_placard')
            ->add('sejour_placard_com')
            ->add('sejour_fenetre')
            ->add('sejour_fenetre_com')
            ->add('sejour_volet')
            ->add('sejour_faience')
            ->add('sejour_faience_com')
            ->add('sejour_paillasse')
            ->add('sejour_paillasse_com')
            ->add('sejour_evier')
            ->add('sejour_evier_com')
            ->add('sejour_robinetterie')
            ->add('sejour_robinetterie_com')
            ->add('sejour_vmc')
            ->add('sejour_vmc_com')
            ->add('sejour_table_cuisson')
            ->add('sejour_table_cuisson_com')
            ->add('sejour_frigo')
            ->add('sejour_frigo_com')
            ->add('sejour_hotte')
            ->add('sejour_hotte_com')
            ->add('sejour_reglette_lumineuse')
            ->add('sejour_reglette_lumineuse_com')
            ->add('sejour_radiateur')
            ->add('sejour_radiateur_com')
            ->add('sejour_meuble_cuisine_bas')
            ->add('sejour_meuble_cuisine_bas_com')
            ->add('sejour_meuble_cuisine_haut')
            ->add('sejour_meuble_cuisine_haut_com')
            ->add('sejour_volet_com')
            ->add('couloir_porte_serrurerie')
            ->add('couloir_porte_serrurerie_com')
            ->add('couloir_plafond')
            ->add('couloir_plafond_com')
            ->add('couloir_revetement_muraux')
            ->add('couloir_revetement_muraux_com')
            ->add('couloir_plinthes')
            ->add('couloir_plinthes_com')
            ->add('couloir_sol')
            ->add('couloir_sol_com')
            ->add('couloir_luminaire')
            ->add('couloir_luminaire_com')
            ->add('couloir_interupt_prise')
            ->add('couloir_interupt_prise_com')
            ->add('couloir_fenetre')
            ->add('couloir_fenetre_com')
            ->add('couloir_volet')
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
