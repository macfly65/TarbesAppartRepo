<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PTRepository")
 */
class PT
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateDemande;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateLecturePresta;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateFinTravaux;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateValidation;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $statut;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comentLocataire;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comentGestionaireToLocataire;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comentGestionaireToPrestataire;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comentPresta;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\prestataire", inversedBy="PTid")
     */
    private $prestataire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\appartement", inversedBy="PTid")
     */
    private $appartement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDemande(): ?\DateTimeInterface
    {
        return $this->dateDemande;
    }

    public function setDateDemande(?\DateTimeInterface $dateDemande): self
    {
        $this->dateDemande = $dateDemande;

        return $this;
    }

    public function getDateLecturePresta(): ?\DateTimeInterface
    {
        return $this->dateLecturePresta;
    }

    public function setDateLecturePresta(?\DateTimeInterface $dateLecturePresta): self
    {
        $this->dateLecturePresta = $dateLecturePresta;

        return $this;
    }

    public function getDateFinTravaux(): ?\DateTimeInterface
    {
        return $this->dateFinTravaux;
    }

    public function setDateFinTravaux(?\DateTimeInterface $dateFinTravaux): self
    {
        $this->dateFinTravaux = $dateFinTravaux;

        return $this;
    }

    public function getDateValidation(): ?\DateTimeInterface
    {
        return $this->dateValidation;
    }

    public function setDateValidation(?\DateTimeInterface $dateValidation): self
    {
        $this->dateValidation = $dateValidation;

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

    public function getComentLocataire(): ?string
    {
        return $this->comentLocataire;
    }

    public function setComentLocataire(?string $comentLocataire): self
    {
        $this->comentLocataire = $comentLocataire;

        return $this;
    }

    public function getComentGestionaireToLocataire(): ?string
    {
        return $this->comentGestionaireToLocataire;
    }

    public function setComentGestionaireToLocataire(?string $comentGestionaireToLocataire): self
    {
        $this->comentGestionaireToLocataire = $comentGestionaireToLocataire;

        return $this;
    }

    public function getComentGestionaireToPrestataire(): ?string
    {
        return $this->comentGestionaireToPrestataire;
    }

    public function setComentGestionaireToPrestataire(?string $comentGestionaireToPrestataire): self
    {
        $this->comentGestionaireToPrestataire = $comentGestionaireToPrestataire;

        return $this;
    }

    public function getComentPresta(): ?string
    {
        return $this->comentPresta;
    }

    public function setComentPresta(?string $comentPresta): self
    {
        $this->comentPresta = $comentPresta;

        return $this;
    }

    public function getPrestataire(): ?prestataire
    {
        return $this->prestataire;
    }

    public function setPrestataire(?prestataire $prestataire): self
    {
        $this->prestataire = $prestataire;

        return $this;
    }

    public function getAppartement(): ?appartement
    {
        return $this->appartement;
    }

    public function setAppartement(?appartement $appartement): self
    {
        $this->appartement = $appartement;

        return $this;
    }
}
