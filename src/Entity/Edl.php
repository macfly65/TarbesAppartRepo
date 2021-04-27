<?php

namespace App\Entity;

use App\Repository\EdlRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EdlRepository::class)
 */
class Edl
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $entree_sonette_interphone;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $entree_sonette_interphone_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $entree_porte_serrurerie;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $entree_porte_serrurerie_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $entree_plafond;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $entree_plafond_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $entree_revetements_muraux;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $entree_revetements_muraux_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $entree_plinthes;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $entree_plinthes_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $entree_sol;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $entree_sol_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $entree_luminaire;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $entree_luminaire_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $entree_interupt_prise;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $entree_interupt_prise_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $entree_placard;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $entree_placard_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $entree_fenetre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $entree_fenetre_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $entree_volet;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $entree_volet_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sdb_porte_serrurerie;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sdb_porte_serrurerie_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sdb_plafond;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sdb_plafond_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sdb_revetements_muraux;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sdb_revetements_muraux_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sdb_plinthes;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sdb_plinthes_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sdb_sol;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sdb_sol_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sdb_luminaire;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sdb_luminaire_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sdb_interupt_prise;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sdb_interupt_prise_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sdb_radiateur;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sdb_radiateur_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sdb_placard;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sdb_placard_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sdb_fenetre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sdb_fenetre_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sdb_volet;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sdb_volet_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sdb_lavabo;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sdb_lavabo_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sdb_robinetterie_lavabo;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sdb_robinetterie_lavabo_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sdb_douche;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sdb_douche_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sdb_robinetterie_douche;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sdb_robinetterie_douche_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sdb_paroi_douche;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sdb_paroi_douche_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sdb_baignoire;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sdb_baignoire_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sdb_robinetterie_baignoire;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sdb_robinetterie_baignoire_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sdb_faience;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sdb_faience_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sdb_joints;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sdb_joints_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $wc_porte_serrurerie;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $wc_porte_serrurerie_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $wc_plafond;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $wc_plafond_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $wc_revetement_muraux;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $wc_revetement_muraux_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $wc_plinthes;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $wc_plinthes_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $wc_sol;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $wc_sol_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $wc_luminaire;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $wc_luminaire_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $wc_interupt_prise;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $wc_interupt_prise_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $wc_radiateur;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $wc_radiateur_com;

    /**
     * @ORM\Column(type="integer")
     */
    private $wc_cuvette_mecanisme;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $wc_cuvette_mecanisme_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $wc_abattant;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $wc_abattant_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $wc_fenetre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $wc_fenetre_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $wc_volet;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $wc_volet_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $wc_faiences;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $wc_faiences_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $wc_joints;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $wc_jointsÂ_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch1_porte_serrurerie;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch1_porte_serrurerie_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch1_plafond;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch1_plafond_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch1_revetements_muraux;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch1_revetements_muraux_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch1_plinthes;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch1_plinthes_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch1_sol;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch1_sol_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch1_luminaire;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch1_luminaire_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch1_interupt_prise;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch1_interupt_prise_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch1_radiateur;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch1_radiateur_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch1_placard;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch1_placard_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch1_fenetre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch1_fenetre_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch1_volet;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch1_volet_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch2_porte_serrurerie;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch2_porte_serrurerie_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch2_plafond;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch2_plafond_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch2_revetements_muraux;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch2_revetements_muraux_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch2_plinthes;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch2_plinthes_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch2_sol;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch2_sol_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch2_luminaire;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch2_luminaire_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch2_interupt_prise;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch2_interupt_prise_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch2_radiateur;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch2_radiateur_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch2_placard;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch2_placard_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch2_fenetre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch2_fenetreÂ_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch2_volet;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch2_volet_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch3_porte_serrurerie;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch3_porte_serrurerie_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch3_plafond;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch3_plafond_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch3_revetements_muraux;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch3_revetements_muraux_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch3_plinthes;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch3_plinthes_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch3_sol;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch3_sol_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch3_luminaire;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch3_luminaire_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch3_interupt_prise;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch3_interupt_prise_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch3_radiateur;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch3_radiateur_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch3_placard;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch3_placard_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch3_fenetre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch3_fenetre_com;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ch3_volet;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ch3_volet_com;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntreeSonetteInterphone(): ?int
    {
        return $this->entree_sonette_interphone;
    }

    public function setEntreeSonetteInterphone(?int $entree_sonette_interphone): self
    {
        $this->entree_sonette_interphone = $entree_sonette_interphone;

        return $this;
    }

    public function getEntreeSonetteInterphoneCom(): ?string
    {
        return $this->entree_sonette_interphone_com;
    }

    public function setEntreeSonetteInterphoneCom(?string $entree_sonette_interphone_com): self
    {
        $this->entree_sonette_interphone_com = $entree_sonette_interphone_com;

        return $this;
    }

    public function getEntreePorteSerrurerie(): ?int
    {
        return $this->entree_porte_serrurerie;
    }

    public function setEntreePorteSerrurerie(?int $entree_porte_serrurerie): self
    {
        $this->entree_porte_serrurerie = $entree_porte_serrurerie;

        return $this;
    }

    public function getEntreePorteSerrurerieCom(): ?string
    {
        return $this->entree_porte_serrurerie_com;
    }

    public function setEntreePorteSerrurerieCom(?string $entree_porte_serrurerie_com): self
    {
        $this->entree_porte_serrurerie_com = $entree_porte_serrurerie_com;

        return $this;
    }

    public function getEntreePlafond(): ?int
    {
        return $this->entree_plafond;
    }

    public function setEntreePlafond(?int $entrï¿½ee_plafond): self
    {
        $this->entree_plafond = $entrï¿½ee_plafond;

        return $this;
    }

    public function getEntreePlafondCom(): ?string
    {
        return $this->entree_plafond_com;
    }

    public function setEntreePlafondCom(?string $entree_plafond_com): self
    {
        $this->entree_plafond_com = $entree_plafond_com;

        return $this;
    }

    public function getEntreeRevetementsMuraux(): ?int
    {
        return $this->entree_revetements_muraux;
    }

    public function setEntreeRevetementsMuraux(?int $entree_revetements_muraux): self
    {
        $this->entree_revetements_muraux = $entree_revetements_muraux;

        return $this;
    }

    public function getEntreeRevetementsMurauxCom(): ?string
    {
        return $this->entree_revetements_muraux_com;
    }

    public function setEntreeRevetementsMurauxCom(?string $entree_revetements_muraux_com): self
    {
        $this->entree_revetements_muraux_com = $entree_revetements_muraux_com;

        return $this;
    }

    public function getEntreePlinthes(): ?int
    {
        return $this->entree_plinthes;
    }

    public function setEntreePlinthes(?int $entree_plinthes): self
    {
        $this->entree_plinthes = $entree_plinthes;

        return $this;
    }

    public function getEntreePlinthesCom(): ?string
    {
        return $this->entree_plinthes_com;
    }

    public function setEntreePlinthesCom(?string $entree_plinthes_com): self
    {
        $this->entree_plinthes_com = $entree_plinthes_com;

        return $this;
    }

    public function getEntreeSol(): ?int
    {
        return $this->entree_sol;
    }

    public function setEntreeSol(?int $entree_sol): self
    {
        $this->entree_sol = $entree_sol;

        return $this;
    }

    public function getEntreeSolCom(): ?string
    {
        return $this->entree_sol_com;
    }

    public function setEntreeSolCom(?string $entree_sol_com): self
    {
        $this->entree_sol_com = $entree_sol_com;

        return $this;
    }

    public function getEntreeLuminaire(): ?int
    {
        return $this->entree_luminaire;
    }

    public function setEntreeLuminaire(?int $entree_luminaire): self
    {
        $this->entree_luminaire = $entree_luminaire;

        return $this;
    }

    public function getEntreeLuminaireCom(): ?string
    {
        return $this->entree_luminaire_com;
    }

    public function setEntreeLuminaireCom(?string $entree_luminaire_com): self
    {
        $this->entree_luminaire_com = $entree_luminaire_com;

        return $this;
    }

    public function getEntreeInteruptPrise(): ?int
    {
        return $this->entree_interupt_prise;
    }

    public function setEntreeInteruptPrise(?int $entree_interupt_prise): self
    {
        $this->entree_interupt_prise = $entree_interupt_prise;

        return $this;
    }

    public function getEntreeInteruptPriseCom(): ?string
    {
        return $this->entree_interupt_prise_com;
    }

    public function setEntreeInteruptPriseCom(?string $entree_interupt_prise_com): self
    {
        $this->entree_interupt_prise_com = $entree_interupt_prise_com;

        return $this;
    }

    public function getEntreePlacard(): ?int
    {
        return $this->entree_placard;
    }

    public function setEntreePlacard(?int $entree_placard): self
    {
        $this->entree_placard = $entree_placard;

        return $this;
    }

    public function getEntreePlacardCom(): ?string
    {
        return $this->entree_placard_com;
    }

    public function setEntreePlacardCom(?string $entree_placard_com): self
    {
        $this->entree_placard_com = $entree_placard_com;

        return $this;
    }

    public function getEntreeFenetre(): ?int
    {
        return $this->entree_fenetre;
    }

    public function setEntreeFenetre(?int $entree_fenetre): self
    {
        $this->entree_fenetre = $entree_fenetre;

        return $this;
    }

    public function getEntreeFenetreCom(): ?string
    {
        return $this->entree_fenetre_com;
    }

    public function setEntreeFenetreCom(?string $entree_fenetre_com): self
    {
        $this->entree_fenetre_com = $entree_fenetre_com;

        return $this;
    }

    public function getEntreeVolet(): ?int
    {
        return $this->entree_volet;
    }

    public function setEntreeVolet(?int $entree_volet): self
    {
        $this->entree_volet = $entree_volet;

        return $this;
    }

    public function getEntreeVoletCom(): ?string
    {
        return $this->entree_volet_com;
    }

    public function setEntreeVoletCom(?string $entree_volet_com): self
    {
        $this->entree_volet_com = $entree_volet_com;

        return $this;
    }

    public function getSdbPorteSerrurerie(): ?string
    {
        return $this->sdb_porte_serrurerie;
    }

    public function setSdbPorteSerrurerie(?string $sdb_porte_serrurerie): self
    {
        $this->sdb_porte_serrurerie = $sdb_porte_serrurerie;

        return $this;
    }

    public function getSdbPorteSerrurerieCom(): ?string
    {
        return $this->sdb_porte_serrurerie_com;
    }

    public function setSdbPorteSerrurerieCom(?string $sdb_porte_serrurerie_com): self
    {
        $this->sdb_porte_serrurerie_com = $sdb_porte_serrurerie_com;

        return $this;
    }

    public function getSdbPlafond(): ?int
    {
        return $this->sdb_plafond;
    }

    public function setSdbPlafond(?int $sdb_plafond): self
    {
        $this->sdb_plafond = $sdb_plafond;

        return $this;
    }

    public function getSdbPlafondCom(): ?string
    {
        return $this->sdb_plafond_com;
    }

    public function setSdbPlafondCom(?string $sdb_plafond_com): self
    {
        $this->sdb_plafond_com = $sdb_plafond_com;

        return $this;
    }

    public function getSdbRevetementsMuraux(): ?int
    {
        return $this->sdb_revetements_muraux;
    }

    public function setSdbRevetementsMuraux(?int $sdb_revetements_muraux): self
    {
        $this->sdb_revetements_muraux = $sdb_revetements_muraux;

        return $this;
    }

    public function getSdbRevetementsMurauxCom(): ?string
    {
        return $this->sdb_revetements_muraux_com;
    }

    public function setSdbRevetementsMurauxCom(?string $sdb_revetements_muraux_com): self
    {
        $this->sdb_revetements_muraux_com = $sdb_revetements_muraux_com;

        return $this;
    }

    public function getSdbPlinthes(): ?int
    {
        return $this->sdb_plinthes;
    }

    public function setSdbPlinthes(?int $sdb_plinthes): self
    {
        $this->sdb_plinthes = $sdb_plinthes;

        return $this;
    }

    public function getSdbPlinthesCom(): ?string
    {
        return $this->sdb_plinthes_com;
    }

    public function setSdbPlinthesCom(?string $sdb_plinthes_com): self
    {
        $this->sdb_plinthes_com = $sdb_plinthes_com;

        return $this;
    }

    public function getSdbSol(): ?int
    {
        return $this->sdb_sol;
    }

    public function setSdbSol(?int $sdb_sol): self
    {
        $this->sdb_sol = $sdb_sol;

        return $this;
    }

    public function getSdbSolCom(): ?string
    {
        return $this->sdb_sol_com;
    }

    public function setSdbSolCom(?string $sdb_sol_com): self
    {
        $this->sdb_sol_com = $sdb_sol_com;

        return $this;
    }

    public function getSdbLuminaire(): ?int
    {
        return $this->sdb_luminaire;
    }

    public function setSdbLuminaire(?int $sdb_luminaire): self
    {
        $this->sdb_luminaire = $sdb_luminaire;

        return $this;
    }

    public function getSdbLuminaireCom(): ?string
    {
        return $this->sdb_luminaire_com;
    }

    public function setSdbLuminaireCom(?string $sdb_luminaire_com): self
    {
        $this->sdb_luminaire_com = $sdb_luminaire_com;

        return $this;
    }

    public function getSdbInteruptPrise(): ?int
    {
        return $this->sdb_interupt_prise;
    }

    public function setSdbInteruptPrise(?int $sdb_interupt_prise): self
    {
        $this->sdb_interupt_prise = $sdb_interupt_prise;

        return $this;
    }

    public function getSdbInteruptPriseCom(): ?string
    {
        return $this->sdb_interupt_prise_com;
    }

    public function setSdbInteruptPriseCom(?string $sdb_interupt_prise_com): self
    {
        $this->sdb_interupt_prise_com = $sdb_interupt_prise_com;

        return $this;
    }

    public function getSdbRadiateur(): ?int
    {
        return $this->sdb_radiateur;
    }

    public function setSdbRadiateur(?int $sdb_radiateur): self
    {
        $this->sdb_radiateur = $sdb_radiateur;

        return $this;
    }

    public function getSdbRadiateurCom(): ?string
    {
        return $this->sdb_radiateur_com;
    }

    public function setSdbRadiateurCom(?string $sdb_radiateur_com): self
    {
        $this->sdb_radiateur_com = $sdb_radiateur_com;

        return $this;
    }

    public function getSdbPlacard(): ?int
    {
        return $this->sdb_placard;
    }

    public function setSdbPlacard(?int $sdb_placard): self
    {
        $this->sdb_placard = $sdb_placard;

        return $this;
    }

    public function getSdbPlacardCom(): ?string
    {
        return $this->sdb_placard_com;
    }

    public function setSdbPlacardCom(?string $sdb_placard_com): self
    {
        $this->sdb_placard_com = $sdb_placard_com;

        return $this;
    }

    public function getSdbFenetre(): ?int
    {
        return $this->sdb_fenetre;
    }

    public function setSdbFenetre(?int $sdb_fenetre): self
    {
        $this->sdb_fenetre = $sdb_fenetre;

        return $this;
    }

    public function getSdbFenetreCom(): ?string
    {
        return $this->sdb_fenetre_com;
    }

    public function setSdbFenetreCom(?string $sdb_fenetre_com): self
    {
        $this->sdb_fenetre_com = $sdb_fenetre_com;

        return $this;
    }

    public function getSdbVolet(): ?int
    {
        return $this->sdb_volet;
    }

    public function setSdbVolet(?int $sdb_volet): self
    {
        $this->sdb_volet = $sdb_volet;

        return $this;
    }

    public function getSdbVoletCom(): ?string
    {
        return $this->sdb_volet_com;
    }

    public function setSdbVoletCom(?string $sdb_volet_com): self
    {
        $this->sdb_volet_com = $sdb_volet_com;

        return $this;
    }

    public function getSdbLavabo(): ?int
    {
        return $this->sdb_lavabo;
    }

    public function setSdbLavabo(?int $sdb_lavabo): self
    {
        $this->sdb_lavabo = $sdb_lavabo;

        return $this;
    }

    public function getSdbLavaboCom(): ?string
    {
        return $this->sdb_lavabo_com;
    }

    public function setSdbLavaboCom(?string $sdb_lavabo_com): self
    {
        $this->sdb_lavabo_com = $sdb_lavabo_com;

        return $this;
    }

    public function getSdbRobinetterieLavabo(): ?int
    {
        return $this->sdb_robinetterie_lavabo;
    }

    public function setSdbRobinetterieLavabo(?int $sdb_robinetterie_lavabo): self
    {
        $this->sdb_robinetterie_lavabo = $sdb_robinetterie_lavabo;

        return $this;
    }

    public function getSdbRobinetterieLavaboCom(): ?string
    {
        return $this->sdb_robinetterie_lavabo_com;
    }

    public function setSdbRobinetterieLavaboCom(?string $sdb_robinetterie_lavabo_com): self
    {
        $this->sdb_robinetterie_lavabo_com = $sdb_robinetterie_lavabo_com;

        return $this;
    }

    public function getSdbDouche(): ?int
    {
        return $this->sdb_douche;
    }

    public function setSdbDouche(int $sdb_douche): self
    {
        $this->sdb_douche = $sdb_douche;

        return $this;
    }

    public function getSdbDoucheCom(): ?string
    {
        return $this->sdb_douche_com;
    }

    public function setSdbDoucheCom(?string $sdb_douche_com): self
    {
        $this->sdb_douche_com = $sdb_douche_com;

        return $this;
    }

    public function getSdbRobinetterieDouche(): ?int
    {
        return $this->sdb_robinetterie_douche;
    }

    public function setSdbRobinetterieDouche(?int $sdb_robinetterie_douche): self
    {
        $this->sdb_robinetterie_douche = $sdb_robinetterie_douche;

        return $this;
    }

    public function getSdbRobinetterieDoucheCom(): ?string
    {
        return $this->sdb_robinetterie_douche_com;
    }

    public function setSdbRobinetterieDoucheCom(?string $sdb_robinetterie_douche_com): self
    {
        $this->sdb_robinetterie_douche_com = $sdb_robinetterie_douche_com;

        return $this;
    }

    public function getSdbParoiDouche(): ?int
    {
        return $this->sdb_paroi_douche;
    }

    public function setSdbParoiDouche(?int $sdb_paroi_douche): self
    {
        $this->sdb_paroi_douche = $sdb_paroi_douche;

        return $this;
    }

    public function getSdbParoiDoucheCom(): ?string
    {
        return $this->sdb_paroi_douche_com;
    }

    public function setSdbParoiDoucheCom(?string $sdb_paroi_douche_com): self
    {
        $this->sdb_paroi_douche_com = $sdb_paroi_douche_com;

        return $this;
    }

    public function getSdbBaignoire(): ?int
    {
        return $this->sdb_baignoire;
    }

    public function setSdbBaignoire(?int $sdb_baignoire): self
    {
        $this->sdb_baignoire = $sdb_baignoire;

        return $this;
    }

    public function getSdbBaignoireCom(): ?string
    {
        return $this->sdb_baignoire_com;
    }

    public function setSdbBaignoireCom(?string $sdb_baignoire_com): self
    {
        $this->sdb_baignoire_com = $sdb_baignoire_com;

        return $this;
    }

    public function getSdbRobinetterieBaignoire(): ?int
    {
        return $this->sdb_robinetterie_baignoire;
    }

    public function setSdbRobinetterieBaignoire(?int $sdb_robinetterie_baignoire): self
    {
        $this->sdb_robinetterie_baignoire = $sdb_robinetterie_baignoire;

        return $this;
    }

    public function getSdbRobinetterieBaignoireCom(): ?string
    {
        return $this->sdb_robinetterie_baignoire_com;
    }

    public function setSdbRobinetterieBaignoireCom(?string $sdb_robinetterie_baignoire_com): self
    {
        $this->sdb_robinetterie_baignoire_com = $sdb_robinetterie_baignoire_com;

        return $this;
    }

    public function getSdbFaience(): ?int
    {
        return $this->sdb_faience;
    }

    public function setSdbFaience(?int $sdb_faience): self
    {
        $this->sdb_faience = $sdb_faience;

        return $this;
    }

    public function getSdbFaienceCom(): ?string
    {
        return $this->sdb_faience_com;
    }

    public function setSdbFaienceCom(?string $sdb_faience_com): self
    {
        $this->sdb_faience_com = $sdb_faience_com;

        return $this;
    }

    public function getSdbJoints(): ?int
    {
        return $this->sdb_joints;
    }

    public function setSdbJoints(?int $sdb_joints): self
    {
        $this->sdb_joints = $sdb_joints;

        return $this;
    }

    public function getSdbJointsCom(): ?string
    {
        return $this->sdb_joints_com;
    }

    public function setSdbJointsCom(?string $sdb_joints_com): self
    {
        $this->sdb_joints_com = $sdb_joints_com;

        return $this;
    }

    public function getWcPorteSerrurerie(): ?int
    {
        return $this->wc_porte_serrurerie;
    }

    public function setWcPorteSerrurerie(?int $wc_porte_serrurerie): self
    {
        $this->wc_porte_serrurerie = $wc_porte_serrurerie;

        return $this;
    }

    public function getWcPorteSerrurerieCom(): ?string
    {
        return $this->wc_porte_serrurerie_com;
    }

    public function setWcPorteSerrurerieCom(?string $wc_porte_serrurerie_com): self
    {
        $this->wc_porte_serrurerie_com = $wc_porte_serrurerie_com;

        return $this;
    }

    public function getWcPlafond(): ?int
    {
        return $this->wc_plafond;
    }

    public function setWcPlafond(?int $wc_plafond): self
    {
        $this->wc_plafond = $wc_plafond;

        return $this;
    }

    public function getWcPlafondCom(): ?string
    {
        return $this->wc_plafond_com;
    }

    public function setWcPlafondCom(?string $wc_plafond_com): self
    {
        $this->wc_plafond_com = $wc_plafond_com;

        return $this;
    }

    public function getWcRevetementMuraux(): ?int
    {
        return $this->wc_revetement_muraux;
    }

    public function setWcRevetementMuraux(?int $wc_revetement_muraux): self
    {
        $this->wc_revetement_muraux = $wc_revetement_muraux;

        return $this;
    }

    public function getWcRevetementMurauxCom(): ?string
    {
        return $this->wc_revetement_muraux_com;
    }

    public function setWcRevetementMurauxCom(?string $wc_revetement_muraux_com): self
    {
        $this->wc_revetement_muraux_com = $wc_revetement_muraux_com;

        return $this;
    }

    public function getWcPlinthes(): ?int
    {
        return $this->wc_plinthes;
    }

    public function setWcPlinthes(?int $wc_plinthes): self
    {
        $this->wc_plinthes = $wc_plinthes;

        return $this;
    }

    public function getWcPlinthesCom(): ?string
    {
        return $this->wc_plinthes_com;
    }

    public function setWcPlinthesCom(?string $wc_plinthes_com): self
    {
        $this->wc_plinthes_com = $wc_plinthes_com;

        return $this;
    }

    public function getWcSol(): ?int
    {
        return $this->wc_sol;
    }

    public function setWcSol(?int $wc_sol): self
    {
        $this->wc_sol = $wc_sol;

        return $this;
    }

    public function getWcSolCom(): ?string
    {
        return $this->wc_sol_com;
    }

    public function setWcSolCom(?string $wc_sol_com): self
    {
        $this->wc_sol_com = $wc_sol_com;

        return $this;
    }

    public function getWcLuminaire(): ?int
    {
        return $this->wc_luminaire;
    }

    public function setWcLuminaire(?int $wc_luminaire): self
    {
        $this->wc_luminaire = $wc_luminaire;

        return $this;
    }

    public function getWcLuminaireCom(): ?string
    {
        return $this->wc_luminaire_com;
    }

    public function setWcLuminaireCom(?string $wc_luminaire_com): self
    {
        $this->wc_luminaire_com = $wc_luminaire_com;

        return $this;
    }

    public function getWcInteruptPrise(): ?int
    {
        return $this->wc_interupt_prise;
    }

    public function setWcInteruptPrise(?int $wc_interupt_prise): self
    {
        $this->wc_interupt_prise = $wc_interupt_prise;

        return $this;
    }

    public function getWcInteruptPriseCom(): ?string
    {
        return $this->wc_interupt_prise_com;
    }

    public function setWcInteruptPriseCom(?string $wc_interupt_prise_com): self
    {
        $this->wc_interupt_prise_com = $wc_interupt_prise_com;

        return $this;
    }

    public function getWcRadiateur(): ?int
    {
        return $this->wc_radiateur;
    }

    public function setWcRadiateur(?int $wc_radiateur): self
    {
        $this->wc_radiateur = $wc_radiateur;

        return $this;
    }

    public function getWcRadiateurCom(): ?string
    {
        return $this->wc_radiateur_com;
    }

    public function setWcRadiateurCom(?string $wc_radiateur_com): self
    {
        $this->wc_radiateur_com = $wc_radiateur_com;

        return $this;
    }

    public function getWcCuvetteMecanisme(): ?int
    {
        return $this->wc_cuvette_mecanisme;
    }

    public function setWcCuvetteMecanisme(int $wc_cuvette_mecanisme): self
    {
        $this->wc_cuvette_mecanisme = $wc_cuvette_mecanisme;

        return $this;
    }

    public function getWcCuvetteMecanismeCom(): ?string
    {
        return $this->wc_cuvette_mecanisme_com;
    }

    public function setWcCuvetteMecanismeCom(?string $wc_cuvette_mecanisme_com): self
    {
        $this->wc_cuvette_mecanisme_com = $wc_cuvette_mecanisme_com;

        return $this;
    }

    public function getWcAbattant(): ?int
    {
        return $this->wc_abattant;
    }

    public function setWcAbattant(?int $wc_abattant): self
    {
        $this->wc_abattant = $wc_abattant;

        return $this;
    }

    public function getWcAbattantCom(): ?string
    {
        return $this->wc_abattant_com;
    }

    public function setWcAbattantCom(?string $wc_abattant_com): self
    {
        $this->wc_abattant_com = $wc_abattant_com;

        return $this;
    }

    public function getWcFenetre(): ?int
    {
        return $this->wc_fenetre;
    }

    public function setWcFenetre(?int $wc_fenetre): self
    {
        $this->wc_fenetre = $wc_fenetre;

        return $this;
    }

    public function getWcFenetreCom(): ?string
    {
        return $this->wc_fenetre_com;
    }

    public function setWcFenetreCom(?string $wc_fenetre_com): self
    {
        $this->wc_fenetre_com = $wc_fenetre_com;

        return $this;
    }

    public function getWcVolet(): ?int
    {
        return $this->wc_volet;
    }

    public function setWcVolet(?int $wc_volet): self
    {
        $this->wc_volet = $wc_volet;

        return $this;
    }

    public function getWcVoletCom(): ?string
    {
        return $this->wc_volet_com;
    }

    public function setWcVoletCom(?string $wc_volet_com): self
    {
        $this->wc_volet_com = $wc_volet_com;

        return $this;
    }

    public function getWcFaiences(): ?int
    {
        return $this->wc_faiences;
    }

    public function setWcFaiences(?int $wc_faiences): self
    {
        $this->wc_faiences = $wc_faiences;

        return $this;
    }

    public function getWcFaiencesCom(): ?string
    {
        return $this->wc_faiences_com;
    }

    public function setWcFaiencesCom(?string $wc_faiences_com): self
    {
        $this->wc_faiences_com = $wc_faiences_com;

        return $this;
    }

    public function getWcJoints(): ?int
    {
        return $this->wc_joints;
    }

    public function setWcJoints(?int $wc_joints): self
    {
        $this->wc_joints = $wc_joints;

        return $this;
    }

    public function getWcJointsÂCom(): ?string
    {
        return $this->wc_jointsÂ_com;
    }

    public function setWcJointsÂCom(?string $wc_jointsÂ_com): self
    {
        $this->wc_jointsÂ_com = $wc_jointsÂ_com;

        return $this;
    }

    public function getCh1PorteSerrurerie(): ?int
    {
        return $this->ch1_porte_serrurerie;
    }

    public function setCh1PorteSerrurerie(?int $ch1_porte_serrurerie): self
    {
        $this->ch1_porte_serrurerie = $ch1_porte_serrurerie;

        return $this;
    }

    public function getCh1PorteSerrurerieCom(): ?string
    {
        return $this->ch1_porte_serrurerie_com;
    }

    public function setCh1PorteSerrurerieCom(?string $ch1_porte_serrurerie_com): self
    {
        $this->ch1_porte_serrurerie_com = $ch1_porte_serrurerie_com;

        return $this;
    }

    public function getCh1Plafond(): ?int
    {
        return $this->ch1_plafond;
    }

    public function setCh1Plafond(?int $ch1_plafond): self
    {
        $this->ch1_plafond = $ch1_plafond;

        return $this;
    }

    public function getCh1PlafondCom(): ?string
    {
        return $this->ch1_plafond_com;
    }

    public function setCh1PlafondCom(?string $ch1_plafond_com): self
    {
        $this->ch1_plafond_com = $ch1_plafond_com;

        return $this;
    }

    public function getCh1RevetementsMuraux(): ?int
    {
        return $this->ch1_revetements_muraux;
    }

    public function setCh1RevetementsMuraux(?int $ch1_revetements_muraux): self
    {
        $this->ch1_revetements_muraux = $ch1_revetements_muraux;

        return $this;
    }

    public function getCh1RevetementsMurauxCom(): ?string
    {
        return $this->ch1_revetements_muraux_com;
    }

    public function setCh1RevetementsMurauxCom(?string $ch1_revetements_muraux_com): self
    {
        $this->ch1_revetements_muraux_com = $ch1_revetements_muraux_com;

        return $this;
    }

    public function getCh1Plinthes(): ?int
    {
        return $this->ch1_plinthes;
    }

    public function setCh1Plinthes(?int $ch1_plinthes): self
    {
        $this->ch1_plinthes = $ch1_plinthes;

        return $this;
    }

    public function getCh1PlinthesCom(): ?string
    {
        return $this->ch1_plinthes_com;
    }

    public function setCh1PlinthesCom(?string $ch1_plinthes_com): self
    {
        $this->ch1_plinthes_com = $ch1_plinthes_com;

        return $this;
    }

    public function getCh1Sol(): ?int
    {
        return $this->ch1_sol;
    }

    public function setCh1Sol(?int $ch1_sol): self
    {
        $this->ch1_sol = $ch1_sol;

        return $this;
    }

    public function getCh1SolCom(): ?string
    {
        return $this->ch1_sol_com;
    }

    public function setCh1SolCom(?string $ch1_sol_com): self
    {
        $this->ch1_sol_com = $ch1_sol_com;

        return $this;
    }

    public function getCh1Luminaire(): ?int
    {
        return $this->ch1_luminaire;
    }

    public function setCh1Luminaire(?int $ch1_luminaire): self
    {
        $this->ch1_luminaire = $ch1_luminaire;

        return $this;
    }

    public function getCh1LuminaireCom(): ?string
    {
        return $this->ch1_luminaire_com;
    }

    public function setCh1LuminaireCom(?string $ch1_luminaire_com): self
    {
        $this->ch1_luminaire_com = $ch1_luminaire_com;

        return $this;
    }

    public function getCh1InteruptPrise(): ?int
    {
        return $this->ch1_interupt_prise;
    }

    public function setCh1InteruptPrise(?int $ch1_interupt_prise): self
    {
        $this->ch1_interupt_prise = $ch1_interupt_prise;

        return $this;
    }

    public function getCh1InteruptPriseCom(): ?string
    {
        return $this->ch1_interupt_prise_com;
    }

    public function setCh1InteruptPriseCom(?string $ch1_interupt_prise_com): self
    {
        $this->ch1_interupt_prise_com = $ch1_interupt_prise_com;

        return $this;
    }

    public function getCh1Radiateur(): ?int
    {
        return $this->ch1_radiateur;
    }

    public function setCh1Radiateur(?int $ch1_radiateur): self
    {
        $this->ch1_radiateur = $ch1_radiateur;

        return $this;
    }

    public function getCh1RadiateurCom(): ?int
    {
        return $this->ch1_radiateur_com;
    }

    public function setCh1RadiateurCom(?int $ch1_radiateur_com): self
    {
        $this->ch1_radiateur_com = $ch1_radiateur_com;

        return $this;
    }

    public function getCh1Placard(): ?int
    {
        return $this->ch1_placard;
    }

    public function setCh1Placard(?int $ch1_placard): self
    {
        $this->ch1_placard = $ch1_placard;

        return $this;
    }

    public function getCh1PlacardCom(): ?string
    {
        return $this->ch1_placard_com;
    }

    public function setCh1PlacardCom(?string $ch1_placard_com): self
    {
        $this->ch1_placard_com = $ch1_placard_com;

        return $this;
    }

    public function getCh1Fenetre(): ?int
    {
        return $this->ch1_fenetre;
    }

    public function setCh1Fenetre(?int $ch1_fenetre): self
    {
        $this->ch1_fenetre = $ch1_fenetre;

        return $this;
    }

    public function getCh1FenetreCom(): ?string
    {
        return $this->ch1_fenetre_com;
    }

    public function setCh1FenetreCom(?string $ch1_fenetre_com): self
    {
        $this->ch1_fenetre_com = $ch1_fenetre_com;

        return $this;
    }

    public function getCh1Volet(): ?int
    {
        return $this->ch1_volet;
    }

    public function setCh1Volet(?int $ch1_volet): self
    {
        $this->ch1_volet = $ch1_volet;

        return $this;
    }

    public function getCh1VoletCom(): ?string
    {
        return $this->ch1_volet_com;
    }

    public function setCh1VoletCom(?string $ch1_volet_com): self
    {
        $this->ch1_volet_com = $ch1_volet_com;

        return $this;
    }

    public function getCh2PorteSerrurerie(): ?int
    {
        return $this->ch2_porte_serrurerie;
    }

    public function setCh2PorteSerrurerie(?int $ch2_porte_serrurerie): self
    {
        $this->ch2_porte_serrurerie = $ch2_porte_serrurerie;

        return $this;
    }

    public function getCh2PorteSerrurerieCom(): ?string
    {
        return $this->ch2_porte_serrurerie_com;
    }

    public function setCh2PorteSerrurerieCom(?string $ch2_porte_serrurerie_com): self
    {
        $this->ch2_porte_serrurerie_com = $ch2_porte_serrurerie_com;

        return $this;
    }

    public function getCh2Plafond(): ?int
    {
        return $this->ch2_plafond;
    }

    public function setCh2Plafond(?int $ch2_plafond): self
    {
        $this->ch2_plafond = $ch2_plafond;

        return $this;
    }

    public function getCh2PlafondCom(): ?string
    {
        return $this->ch2_plafond_com;
    }

    public function setCh2PlafondCom(?string $ch2_plafond_com): self
    {
        $this->ch2_plafond_com = $ch2_plafond_com;

        return $this;
    }

    public function getCh2RevetementsMuraux(): ?int
    {
        return $this->ch2_revetements_muraux;
    }

    public function setCh2RevetementsMuraux(?int $ch2_revetements_muraux): self
    {
        $this->ch2_revetements_muraux = $ch2_revetements_muraux;

        return $this;
    }

    public function getCh2RevetementsMurauxCom(): ?string
    {
        return $this->ch2_revetements_muraux_com;
    }

    public function setCh2RevetementsMurauxCom(?string $ch2_revetements_muraux_com): self
    {
        $this->ch2_revetements_muraux_com = $ch2_revetements_muraux_com;

        return $this;
    }

    public function getCh2Plinthes(): ?int
    {
        return $this->ch2_plinthes;
    }

    public function setCh2Plinthes(?int $ch2_plinthes): self
    {
        $this->ch2_plinthes = $ch2_plinthes;

        return $this;
    }

    public function getCh2PlinthesCom(): ?string
    {
        return $this->ch2_plinthes_com;
    }

    public function setCh2PlinthesCom(?string $ch2_plinthes_com): self
    {
        $this->ch2_plinthes_com = $ch2_plinthes_com;

        return $this;
    }

    public function getCh2Sol(): ?int
    {
        return $this->ch2_sol;
    }

    public function setCh2Sol(?int $ch2_sol): self
    {
        $this->ch2_sol = $ch2_sol;

        return $this;
    }

    public function getCh2SolCom(): ?string
    {
        return $this->ch2_sol_com;
    }

    public function setCh2SolCom(?string $ch2_sol_com): self
    {
        $this->ch2_sol_com = $ch2_sol_com;

        return $this;
    }

    public function getCh2Luminaire(): ?int
    {
        return $this->ch2_luminaire;
    }

    public function setCh2Luminaire(?int $ch2_luminaire): self
    {
        $this->ch2_luminaire = $ch2_luminaire;

        return $this;
    }

    public function getCh2LuminaireCom(): ?string
    {
        return $this->ch2_luminaire_com;
    }

    public function setCh2LuminaireCom(?string $ch2_luminaire_com): self
    {
        $this->ch2_luminaire_com = $ch2_luminaire_com;

        return $this;
    }

    public function getCh2InteruptPrise(): ?int
    {
        return $this->ch2_interupt_prise;
    }

    public function setCh2InteruptPrise(?int $ch2_interupt_prise): self
    {
        $this->ch2_interupt_prise = $ch2_interupt_prise;

        return $this;
    }

    public function getCh2InteruptPriseCom(): ?string
    {
        return $this->ch2_interupt_prise_com;
    }

    public function setCh2InteruptPriseCom(?string $ch2_interupt_prise_com): self
    {
        $this->ch2_interupt_prise_com = $ch2_interupt_prise_com;

        return $this;
    }

    public function getCh2Radiateur(): ?int
    {
        return $this->ch2_radiateur;
    }

    public function setCh2Radiateur(?int $ch2_radiateur): self
    {
        $this->ch2_radiateur = $ch2_radiateur;

        return $this;
    }

    public function getCh2RadiateurCom(): ?string
    {
        return $this->ch2_radiateur_com;
    }

    public function setCh2RadiateurCom(?string $ch2_radiateur_com): self
    {
        $this->ch2_radiateur_com = $ch2_radiateur_com;

        return $this;
    }

    public function getCh2Placard(): ?int
    {
        return $this->ch2_placard;
    }

    public function setCh2Placard(?int $ch2_placard): self
    {
        $this->ch2_placard = $ch2_placard;

        return $this;
    }

    public function getCh2PlacardCom(): ?string
    {
        return $this->ch2_placard_com;
    }

    public function setCh2PlacardCom(?string $ch2_placard_com): self
    {
        $this->ch2_placard_com = $ch2_placard_com;

        return $this;
    }

    public function getCh2Fenetre(): ?int
    {
        return $this->ch2_fenetre;
    }

    public function setCh2Fenetre(?int $ch2_fenetre): self
    {
        $this->ch2_fenetre = $ch2_fenetre;

        return $this;
    }

    public function getCh2FenetreÂCom(): ?string
    {
        return $this->ch2_fenetreÂ_com;
    }

    public function setCh2FenetreÂCom(?string $ch2_fenetreÂ_com): self
    {
        $this->ch2_fenetreÂ_com = $ch2_fenetreÂ_com;

        return $this;
    }

    public function getCh2Volet(): ?int
    {
        return $this->ch2_volet;
    }

    public function setCh2Volet(?int $ch2_volet): self
    {
        $this->ch2_volet = $ch2_volet;

        return $this;
    }

    public function getCh2VoletCom(): ?string
    {
        return $this->ch2_volet_com;
    }

    public function setCh2VoletCom(?string $ch2_volet_com): self
    {
        $this->ch2_volet_com = $ch2_volet_com;

        return $this;
    }

    public function getCh3PorteSerrurerie(): ?int
    {
        return $this->ch3_porte_serrurerie;
    }

    public function setCh3PorteSerrurerie(?int $ch3_porte_serrurerie): self
    {
        $this->ch3_porte_serrurerie = $ch3_porte_serrurerie;

        return $this;
    }

    public function getCh3PorteSerrurerieCom(): ?string
    {
        return $this->ch3_porte_serrurerie_com;
    }

    public function setCh3PorteSerrurerieCom(?string $ch3_porte_serrurerie_com): self
    {
        $this->ch3_porte_serrurerie_com = $ch3_porte_serrurerie_com;

        return $this;
    }

    public function getCh3Plafond(): ?int
    {
        return $this->ch3_plafond;
    }

    public function setCh3Plafond(?int $ch3_plafond): self
    {
        $this->ch3_plafond = $ch3_plafond;

        return $this;
    }

    public function getCh3PlafondCom(): ?string
    {
        return $this->ch3_plafond_com;
    }

    public function setCh3PlafondCom(?string $ch3_plafond_com): self
    {
        $this->ch3_plafond_com = $ch3_plafond_com;

        return $this;
    }

    public function getCh3RevetementsMuraux(): ?int
    {
        return $this->ch3_revetements_muraux;
    }

    public function setCh3RevetementsMuraux(?int $ch3_revetements_muraux): self
    {
        $this->ch3_revetements_muraux = $ch3_revetements_muraux;

        return $this;
    }

    public function getCh3RevetementsMurauxCom(): ?string
    {
        return $this->ch3_revetements_muraux_com;
    }

    public function setCh3RevetementsMurauxCom(?string $ch3_revetements_muraux_com): self
    {
        $this->ch3_revetements_muraux_com = $ch3_revetements_muraux_com;

        return $this;
    }

    public function getCh3Plinthes(): ?int
    {
        return $this->ch3_plinthes;
    }

    public function setCh3Plinthes(?int $ch3_plinthes): self
    {
        $this->ch3_plinthes = $ch3_plinthes;

        return $this;
    }

    public function getCh3PlinthesCom(): ?string
    {
        return $this->ch3_plinthes_com;
    }

    public function setCh3PlinthesCom(?string $ch3_plinthes_com): self
    {
        $this->ch3_plinthes_com = $ch3_plinthes_com;

        return $this;
    }

    public function getCh3Sol(): ?int
    {
        return $this->ch3_sol;
    }

    public function setCh3Sol(?int $ch3_sol): self
    {
        $this->ch3_sol = $ch3_sol;

        return $this;
    }

    public function getCh3SolCom(): ?string
    {
        return $this->ch3_sol_com;
    }

    public function setCh3SolCom(?string $ch3_sol_com): self
    {
        $this->ch3_sol_com = $ch3_sol_com;

        return $this;
    }

    public function getCh3Luminaire(): ?int
    {
        return $this->ch3_luminaire;
    }

    public function setCh3Luminaire(?int $ch3_luminaire): self
    {
        $this->ch3_luminaire = $ch3_luminaire;

        return $this;
    }

    public function getCh3LuminaireCom(): ?string
    {
        return $this->ch3_luminaire_com;
    }

    public function setCh3LuminaireCom(?string $ch3_luminaire_com): self
    {
        $this->ch3_luminaire_com = $ch3_luminaire_com;

        return $this;
    }

    public function getCh3InteruptPrise(): ?int
    {
        return $this->ch3_interupt_prise;
    }

    public function setCh3InteruptPrise(?int $ch3_interupt_prise): self
    {
        $this->ch3_interupt_prise = $ch3_interupt_prise;

        return $this;
    }

    public function getCh3InteruptPriseCom(): ?string
    {
        return $this->ch3_interupt_prise_com;
    }

    public function setCh3InteruptPriseCom(?string $ch3_interupt_prise_com): self
    {
        $this->ch3_interupt_prise_com = $ch3_interupt_prise_com;

        return $this;
    }

    public function getCh3Radiateur(): ?int
    {
        return $this->ch3_radiateur;
    }

    public function setCh3Radiateur(?int $ch3_radiateur): self
    {
        $this->ch3_radiateur = $ch3_radiateur;

        return $this;
    }

    public function getCh3RadiateurCom(): ?string
    {
        return $this->ch3_radiateur_com;
    }

    public function setCh3RadiateurCom(?string $ch3_radiateur_com): self
    {
        $this->ch3_radiateur_com = $ch3_radiateur_com;

        return $this;
    }

    public function getCh3Placard(): ?int
    {
        return $this->ch3_placard;
    }

    public function setCh3Placard(?int $ch3_placard): self
    {
        $this->ch3_placard = $ch3_placard;

        return $this;
    }

    public function getCh3PlacardCom(): ?string
    {
        return $this->ch3_placard_com;
    }

    public function setCh3PlacardCom(?string $ch3_placard_com): self
    {
        $this->ch3_placard_com = $ch3_placard_com;

        return $this;
    }

    public function getCh3Fenetre(): ?int
    {
        return $this->ch3_fenetre;
    }

    public function setCh3Fenetre(?int $ch3_fenetre): self
    {
        $this->ch3_fenetre = $ch3_fenetre;

        return $this;
    }

    public function getCh3FenetreCom(): ?string
    {
        return $this->ch3_fenetre_com;
    }

    public function setCh3FenetreCom(?string $ch3_fenetre_com): self
    {
        $this->ch3_fenetre_com = $ch3_fenetre_com;

        return $this;
    }

    public function getCh3Volet(): ?int
    {
        return $this->ch3_volet;
    }

    public function setCh3Volet(?int $ch3_volet): self
    {
        $this->ch3_volet = $ch3_volet;

        return $this;
    }

    public function getCh3VoletCom(): ?string
    {
        return $this->ch3_volet_com;
    }

    public function setCh3VoletCom(?string $ch3_volet_com): self
    {
        $this->ch3_volet_com = $ch3_volet_com;

        return $this;
    }


}
