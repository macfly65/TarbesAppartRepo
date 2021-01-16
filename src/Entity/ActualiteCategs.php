<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActualiteCategsRepository")
 */
class ActualiteCategs
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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ordre;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $siteUid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $siteLang;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Actualite", mappedBy="categorie")
     */
    private $actualites;

    public function __construct()
    {
        $this->actualites = new ArrayCollection();
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

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(?int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getSiteUid(): ?int
    {
        return $this->siteUid;
    }

    public function setSiteUid(?int $siteUid): self
    {
        $this->siteUid = $siteUid;

        return $this;
    }

    public function getSiteLang(): ?string
    {
        return $this->siteLang;
    }

    public function setSiteLang(?string $siteLang): self
    {
        $this->siteLang = $siteLang;

        return $this;
    }

    /**
     * @return Collection|Actualite[]
     */
    public function getActualites(): Collection
    {
        return $this->actualites;
    }

    public function addActualite(Actualite $actualite): self
    {
        if (!$this->actualites->contains($actualite)) {
            $this->actualites[] = $actualite;
            $actualite->setCategorie($this);
        }

        return $this;
    }

    public function removeActualite(Actualite $actualite): self
    {
        if ($this->actualites->contains($actualite)) {
            $this->actualites->removeElement($actualite);
            // set the owning side to null (unless already changed)
            if ($actualite->getCategorie() === $this) {
                $actualite->setCategorie(null);
            }
        }

        return $this;
    }
}
