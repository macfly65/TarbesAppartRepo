<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PrestataireRepository")
 */
class prestataire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tel;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $statut;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PT", mappedBy="prestataire")
     */
    private $PTid;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateEnvoiPresta;

    public function __construct()
    {
        $this->PTid = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTel()
    {
        return $this->tel;
    }

    public function setTel($tel): self
    {
        $this->tel = $tel;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

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
            $pTid->setPrestataire($this);
        }

        return $this;
    }

    public function removePTid(PT $pTid): self
    {
        if ($this->PTid->contains($pTid)) {
            $this->PTid->removeElement($pTid);
            // set the owning side to null (unless already changed)
            if ($pTid->getPrestataire() === $this) {
                $pTid->setPrestataire(null);
            }
        }

        return $this;
    }

    public function getDateEnvoiPresta(): ?\DateTimeInterface
    {
        return $this->dateEnvoiPresta;
    }

    public function setDateEnvoiPresta(?\DateTimeInterface $dateEnvoiPresta): self
    {
        $this->dateEnvoiPresta = $dateEnvoiPresta;

        return $this;
    }
}
