<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AppartementRepository")
 */
class Appartement
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
    private $numero;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $loyerEtudiant;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $loyerHotelSemaine;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $loyerHotelJour;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $surface;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $etage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $exposition;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $statut;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $disponibilite;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDisponibilite;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AppartementMedias", mappedBy="appartement")
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Residence", inversedBy="appartements")
     */
    private $residence;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Locataire", inversedBy="appartements")
     */
    private $locataire;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PT", mappedBy="appartement")
     */
    private $PTid;

    /**
     * @ORM\Column(type="string", length=400, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $meuble;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $charge;

    /**
     * @ORM\OneToMany(targetEntity=Edl::class, mappedBy="appartement")
     */
    private $edl;



    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->locataire = new ArrayCollection();
        $this->PTid = new ArrayCollection();
        $this->edl = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(?int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getLoyerEtudiant(): ?int
    {
        return $this->loyerEtudiant;
    }

    public function setLoyerEtudiant(?int $loyerEtudiant): self
    {
        $this->loyerEtudiant = $loyerEtudiant;

        return $this;
    }

    public function getLoyerHotelSemaine(): ?int
    {
        return $this->loyerHotelSemaine;
    }

    public function setLoyerHotelSemaine(?int $loyerHotelSemaine): self
    {
        $this->loyerHotelSemaine = $loyerHotelSemaine;

        return $this;
    }

    public function getLoyerHotelJour(): ?int
    {
        return $this->loyerHotelJour;
    }

    public function setLoyerHotelJour(?int $loyerHotelJour): self
    {
        $this->loyerHotelJour = $loyerHotelJour;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSurface(): ?int
    {
        return $this->surface;
    }

    public function setSurface(?int $surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    public function getEtage(): ?int
    {
        return $this->etage;
    }

    public function setEtage(?int $etage): self
    {
        $this->etage = $etage;

        return $this;
    }

    public function getExposition(): ?string
    {
        return $this->exposition;
    }

    public function setExposition(?string $exposition): self
    {
        $this->exposition = $exposition;

        return $this;
    }

    public function getStatut(): ?int
    {
        return $this->statut;
    }

    public function setStatut(?int $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDisponibilite(): ?\DateTimeInterface
    {
        return $this->disponibilite;
    }

    public function setDisponibilite(?\DateTimeInterface $disponibilite): self
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }

    public function getDateDisponibilite(): ?\DateTimeInterface
    {
        return $this->dateDisponibilite;
    }

    public function setDateDisponibilite(?\DateTimeInterface $dateDisponibilite): self
    {
        $this->dateDisponibilite = $dateDisponibilite;

        return $this;
    }

    /**
     * @return Collection|appartementMedias[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(appartementMedias $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setAppartement($this);
        }

        return $this;
    }

    public function removeImage(appartementMedias $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getAppartement() === $this) {
                $image->setAppartement(null);
            }
        }

        return $this;
    }

    public function getResidence(): ?Residence
    {
        return $this->residence;
    }

    public function setResidence(?Residence $residence): self
    {
        $this->residence = $residence;

        return $this;
    }

    /**
     * @return Collection|locataire[]
     */
    public function getLocataire(): Collection
    {
        return $this->locataire;
    }

    public function addLocataire(locataire $locataire): self
    {
        if (!$this->locataire->contains($locataire)) {
            $this->locataire[] = $locataire;
        }

        return $this;
    }

    public function removeLocataire(locataire $locataire): self
    {
        if ($this->locataire->contains($locataire)) {
            $this->locataire->removeElement($locataire);
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|PT[]
     */
    public function getPTid(): Collection
    {
        return $this->PTid;
    }

    public function addPTid(PT $pTid): self
    {
        if (!$this->PTid->contains($pTid)) {
            $this->PTid[] = $pTid;
            $pTid->setAppartement($this);
        }

        return $this;
    }

    public function removePTid(PT $pTid): self
    {
        if ($this->PTid->contains($pTid)) {
            $this->PTid->removeElement($pTid);
            // set the owning side to null (unless already changed)
            if ($pTid->getAppartement() === $this) {
                $pTid->setAppartement(null);
            }
        }

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getMeuble(): ?string
    {
        return $this->meuble;
    }

    public function setMeuble(?string $meuble): self
    {
        $this->meuble = $meuble;

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

    /**
     * @return Collection|Edl[]
     */
    public function getEdl(): Collection
    {
        return $this->edl;
    }

    public function addEdl(Edl $edl): self
    {
        if (!$this->edl->contains($edl)) {
            $this->edl[] = $edl;
            $edl->setAppartement($this);
        }

        return $this;
    }

    public function removeEdl(Edl $edl): self
    {
        if ($this->edl->removeElement($edl)) {
            // set the owning side to null (unless already changed)
            if ($edl->getAppartement() === $this) {
                $edl->setAppartement(null);
            }
        }

        return $this;
    }
}
