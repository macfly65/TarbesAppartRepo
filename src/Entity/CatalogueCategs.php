<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CatalogueCategsRepository")
 */
class CatalogueCategs
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
    private $bandeau;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ordre;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $site_uid;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $siteLang;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Catalogue", mappedBy="categorieId")
     */
    private $catalogues;

    public function __construct()
    {
        $this->catalogues = new ArrayCollection();
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

    public function getBandeau(): ?string
    {
        return $this->bandeau;
    }

    public function setBandeau(?string $bandeau): self
    {
        $this->bandeau = $bandeau;

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
        return $this->site_uid;
    }

    public function setSiteUid(?int $site_uid): self
    {
        $this->site_uid = $site_uid;

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
     * @return Collection|Catalogue[]
     */
    public function getCatalogues(): Collection
    {
        return $this->catalogues;
    }

    public function addCatalogue(Catalogue $catalogue): self
    {
        if (!$this->catalogues->contains($catalogue)) {
            $this->catalogues[] = $catalogue;
            $catalogue->setCategorieId($this);
        }

        return $this;
    }

    public function removeCatalogue(Catalogue $catalogue): self
    {
        if ($this->catalogues->contains($catalogue)) {
            $this->catalogues->removeElement($catalogue);
            // set the owning side to null (unless already changed)
            if ($catalogue->getCategorieId() === $this) {
                $catalogue->setCategorieId(null);
            }
        }

        return $this;
    }
}
