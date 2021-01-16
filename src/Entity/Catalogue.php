<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CatalogueRepository")
 */
class Catalogue
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reference;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ingredients;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $conditionnement;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isOnline;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $siteUid;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $siteLang;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CatalogueCategs", inversedBy="catalogues")
     */
    private $categorieId;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CatalogueMedias", mappedBy="catalogueId")
     */
    private $catalogueMedias;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ordre;

    public function __construct()
    {
        $this->catalogueMedias = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIngredients(): ?string
    {
        return $this->ingredients;
    }

    public function setIngredients(?string $ingredients): self
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function getConditionnement(): ?string
    {
        return $this->conditionnement;
    }

    public function setConditionnement(?string $conditionnement): self
    {
        $this->conditionnement = $conditionnement;

        return $this;
    }

    public function getIsOnline(): ?bool
    {
        return $this->isOnline;
    }

    public function setIsOnline(?bool $isOnline): self
    {
        $this->isOnline = $isOnline;

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

    public function getCategorieId(): ?CatalogueCategs
    {
        return $this->categorieId;
    }

    public function setCategorieId(?CatalogueCategs $categorieId): self
    {
        $this->categorieId = $categorieId;

        return $this;
    }

    /**
     * @return Collection|CatalogueMedias[]
     */
    public function getCatalogueMedias(): Collection
    {
        return $this->catalogueMedias;
    }

    public function addCatalogueMedia(CatalogueMedias $catalogueMedia): self
    {
        if (!$this->catalogueMedias->contains($catalogueMedia)) {
            $this->catalogueMedias[] = $catalogueMedia;
            $catalogueMedia->setCatalogueId($this);
        }

        return $this;
    }

    public function removeCatalogueMedia(CatalogueMedias $catalogueMedia): self
    {
        if ($this->catalogueMedias->contains($catalogueMedia)) {
            $this->catalogueMedias->removeElement($catalogueMedia);
            // set the owning side to null (unless already changed)
            if ($catalogueMedia->getCatalogueId() === $this) {
                $catalogueMedia->setCatalogueId(null);
            }
        }

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
}
