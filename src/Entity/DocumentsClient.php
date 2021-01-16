<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DocumentsClientRepository")
 */
class DocumentsClient
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
    private $nomFichier;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="documentsClients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $clientUser;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\CategsDocuments", mappedBy="document")
     */
    private $categorie;

    public function __construct()
    {
        $this->categorie = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFichier(): ?string
    {
        return $this->nomFichier;
    }

    public function setNomFichier(?string $nomFichier): self
    {
        $this->nomFichier = $nomFichier;

        return $this;
    }

    public function getClientUser(): ?User
    {
        return $this->clientUser;
    }

    public function setClientUser(?User $clientUser): self
    {
        $this->clientUser = $clientUser;

        return $this;
    }

    /**
     * @return Collection|CategsDocuments[]
     */
    public function getCategorie(): Collection
    {
        return $this->categorie;
    }

    public function addCategorie(CategsDocuments $categorie): self
    {
        if (!$this->categorie->contains($categorie)) {
            $this->categorie[] = $categorie;
            $categorie->addDocument($this);
        }

        return $this;
    }

    public function removeCategorie(CategsDocuments $categorie): self
    {
        if ($this->categorie->contains($categorie)) {
            $this->categorie->removeElement($categorie);
            $categorie->removeDocument($this);
        }

        return $this;
    }
}
