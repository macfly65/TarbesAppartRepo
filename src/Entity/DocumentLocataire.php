<?php

namespace App\Entity;

use App\Repository\DocumentLocataireRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocumentLocataireRepository::class)
 */
class DocumentLocataire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=locataire::class, inversedBy="document")
     * @ORM\JoinColumn(nullable=false)
     */
    private $locataire;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getLocataire(): ?locataire
    {
        return $this->locataire;
    }

    public function setLocataire(?locataire $locataire): self
    {
        $this->locataire = $locataire;

        return $this;
    }
}
