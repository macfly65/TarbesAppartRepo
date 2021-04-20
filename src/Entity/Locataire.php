<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocataireRepository")
 */
class Locataire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $statut;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $loyer;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $reduction;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $caution;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateArivee;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateDepart;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $dateResiliation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $civilite;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateNaissancce;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ville;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $codePostal;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $telephoneMobile;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $telephoneFixe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $etablissement;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $immatriculationVehicule;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $iban;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $codeSWIFT;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomCautionneur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenomCautionneur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresseCautionneur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $villeCautionneur;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $codePostalCautionneur;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $telephoneCautionneur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $revenuImposable;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Appartement", mappedBy="locataire")
     */
    private $appartements;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $age;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $dateReservation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $typeCaution;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $charge;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $parking;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $garage;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="locataire", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=DocumentLocataire::class, mappedBy="locataire")
     */
    private $document;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sign;

    public function __construct()
    {
        $this->appartements = new ArrayCollection();
        $this->document = new ArrayCollection();
    }

    public function getId():int
    {
        return $this->id;
    }

    public function getStatut()
    {
        return $this->statut;
    }

    public function setStatut(int $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getLoyer()
    {
        return $this->loyer;
    }

    public function setLoyer(int $loyer): self
    {
        $this->loyer = $loyer;

        return $this;
    }

    public function getReduction()
    {
        return $this->reduction;
    }

    public function setReduction(int $reduction): self
    {
        $this->reduction = $reduction;

        return $this;
    }

    public function getCaution()
    {
        return $this->caution;
    }

    public function setCaution(int $caution): self
    {
        $this->caution = $caution;

        return $this;
    }

    public function getDateArivee()
    {
        return $this->dateArivee;
    }

    public function setDateArivee($dateArivee): self
    {
        $this->dateArivee = $dateArivee;

        return $this;
    }

    public function getDateDepart()
    {
        return $this->dateDepart;
    }

    public function setDateDepart($dateDepart): self
    {
        $this->dateDepart = $dateDepart;

        return $this;
    }

    public function getDateResiliation()
    {
        return $this->dateResiliation;
    }

    public function setDateResiliation($dateResiliation): self
    {
        $this->dateResiliation = $dateResiliation;

        return $this;
    }

    public function getCivilite()
    {
        return $this->civilite;
    }

    public function setCivilite($civilite): self
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getDateNaissancce()
    {
        return $this->dateNaissancce;
    }

    public function setDateNaissancce($dateNaissancce)
    {
        $this->dateNaissancce = $dateNaissancce;

        return $this;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function setPrenom($prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse()
    {
        return $this->adresse;
    }

    public function setAdresse($adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getVille()
    {
        return $this->ville;
    }

    public function setVille($ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCodePostal()    {
        return $this->codePostal;
    }

    public function setCodePostal(int $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getTelephoneMobile()
    {
        return $this->telephoneMobile;
    }

    public function setTelephoneMobile($telephoneMobile): self
    {
        $this->telephoneMobile = $telephoneMobile;

        return $this;
    }

    public function getTelephoneFixe()
    {
        return $this->telephoneFixe;
    }

    public function setTelephoneFixe($telephoneFixe): self
    {
        $this->telephoneFixe = $telephoneFixe;

        return $this;
    }

    public function getEtablissement()
    {
        return $this->etablissement;
    }

    public function setEtablissement($etablissement): self
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    public function getImmatriculationVehicule()
    {
        return $this->immatriculationVehicule;
    }

    public function setImmatriculationVehicule($immatriculationVehicule): self
    {
        $this->immatriculationVehicule = $immatriculationVehicule;

        return $this;
    }

    public function getIban()
    {
        return $this->iban;
    }

    public function setIban($iban): self
    {
        $this->iban = $iban;

        return $this;
    }

    public function getCodeSWIFT()
    {
        return $this->codeSWIFT;
    }

    public function setCodeSWIFT($codeSWIFT): self
    {
        $this->codeSWIFT = $codeSWIFT;

        return $this;
    }

    public function getNomCautionneur()
    {
        return $this->nomCautionneur;
    }

    public function setNomCautionneur($nomCautionneur): self
    {
        $this->nomCautionneur = $nomCautionneur;

        return $this;
    }

    public function getPrenomCautionneur()
    {
        return $this->prenomCautionneur;
    }

    public function setPrenomCautionneur($prenomCautionneur): self
    {
        $this->prenomCautionneur = $prenomCautionneur;

        return $this;
    }

    public function getAdresseCautionneur()
    {
        return $this->adresseCautionneur;
    }

    public function setAdresseCautionneur($adresseCautionneur): self
    {
        $this->adresseCautionneur = $adresseCautionneur;

        return $this;
    }

    public function getVilleCautionneur()
    {
        return $this->villeCautionneur;
    }

    public function setVilleCautionneur($villeCautionneur): self
    {
        $this->villeCautionneur = $villeCautionneur;

        return $this;
    }

    public function getCodePostalCautionneur()
    {
        return $this->codePostalCautionneur;
    }

    public function setCodePostalCautionneur($codePostalCautionneur): self
    {
        $this->codePostalCautionneur = $codePostalCautionneur;

        return $this;
    }

    public function getTelephoneCautionneur()
    {
        return $this->telephoneCautionneur;
    }

    public function setTelephoneCautionneur($telephoneCautionneur): self
    {
        $this->telephoneCautionneur = $telephoneCautionneur;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRevenuImposable()
    {
        return $this->revenuImposable;
    }

    public function setRevenuImposable($revenuImposable): self
    {
        $this->revenuImposable = $revenuImposable;

        return $this;
    }

    /**
     * @return Collection|Appartement[]
     */
    public function getAppartements(): Collection
    {
        return $this->appartements;
    }

    public function addAppartement(Appartement $appartement): self
    {
        if (!$this->appartements->contains($appartement)) {
            $this->appartements[] = $appartement;
            $appartement->addLocataire($this);
        }

        return $this;
    }

    public function removeAppartement(Appartement $appartement): self
    {
        if ($this->appartements->contains($appartement)) {
            $this->appartements->removeElement($appartement);
            $appartement->removeLocataire($this);
        }

        return $this;
    }

    public function getAge(){
        return $this->age;
    }

    public function setAge($age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getDateReservation()
    {
        return $this->dateReservation;
    }

    public function setDateReservation($dateReservation)
    {
        $this->dateReservation = $dateReservation;

        return $this;
    }

    public function getTypeCaution(): ?string
    {
        return $this->typeCaution;
    }

    public function setTypeCaution(?string $typeCaution): self
    {
        $this->typeCaution = $typeCaution;

        return $this;
    }

    public function getCharge(): ?int
    {
        return $this->charge;
    }

    public function setCharge(?int $charge): self
    {
        $this->charge = $charge;

        return $this;
    }

    public function getParking(): ?int
    {
        return $this->parking;
    }

    public function setParking(?int $parking): self
    {
        $this->parking = $parking;

        return $this;
    }

    public function getGarage(): ?int
    {
        return $this->garage;
    }

    public function setGarage(?int $garage): self
    {
        $this->garage = $garage;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(user $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|DocumentLocataire[]
     */
    public function getDocument(): Collection
    {
        return $this->document;
    }

    public function addDocument(DocumentLocataire $document): self
    {
        if (!$this->document->contains($document)) {
            $this->document[] = $document;
            $document->setLocataire($this);
        }

        return $this;
    }

    public function removeDocument(DocumentLocataire $document): self
    {
        if ($this->document->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getLocataire() === $this) {
                $document->setLocataire(null);
            }
        }

        return $this;
    }

    public function getSign(): ?string
    {
        return $this->sign;
    }

    public function setSign(?string $sign): self
    {
        $this->sign = $sign;

        return $this;
    }
}
