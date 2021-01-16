<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CatalogueMediasRepository")
 */
class CatalogueMedias
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Catalogue", inversedBy="catalogueMedias")
     */
    private $catalogueId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomFichier;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titre;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ordre;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCatalogueId(): ?catalogue
    {
        return $this->catalogueId;
    }

    public function setCatalogueId(?catalogue $catalogueId): self
    {
        $this->catalogueId = $catalogueId;

        return $this;
    }

    public function getNomFichier()
    {
        return $this->nomFichier;
    }

    public function setNomFichier($nomFichier): self
    {
        $this->nomFichier = $nomFichier;

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

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

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
