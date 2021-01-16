<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ActualiteRepository")
 */
class Actualite
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
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sousTitre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $texte;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $isValid;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $siteUid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $siteLang;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ActualiteCategs", inversedBy="actualites")
     */
    private $categorie;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ActualiteMedias", mappedBy="Actualite")
     */
    private $actualiteMedias;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CommentaireActualite", mappedBy="actualite")
     */
    private $commentaires;

        public function __construct()
    {
        $this->actualiteMedias = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getSousTitre(): ?string
    {
        return $this->sousTitre;
    }

    public function setSousTitre(?string $sousTitre): self
    {
        $this->sousTitre = $sousTitre;

        return $this;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(?string $texte): self
    {
        $this->texte = $texte;

        return $this;
    }

    public function getIsValid(): ?int
    {
        return $this->isValid;
    }

    public function setIsValid(?int $isValid): self
    {
        $this->isValid = $isValid;

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

    public function getCategorie(): ?ActualiteCategs
    {
        return $this->categorie;
    }

    public function setCategorie(?ActualiteCategs $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }
    
    
    /**
     * @return Collection|ActualiteMedias[]
     */
    public function getActualiteMedias()
    {
        return $this->actualiteMedias;
    }

    public function addActualiteMedia(ActualiteMedias $actualiteMedia): self
    {
        if (!$this->actualiteMedias->contains($actualiteMedia)) {
            $this->actualiteMedias[] = $actualiteMedia;
            $actualiteMedia->setActualite($this);
        }

        return $this;
    }

    public function removeActualiteMedia(ActualiteMedias $actualiteMedia): self
    {
        if ($this->actualiteMedias->contains($actualiteMedia)) {
            $this->actualiteMedias->removeElement($actualiteMedia);
            // set the owning side to null (unless already changed)
            if ($actualiteMedia->getActualite() === $this) {
                $actualiteMedia->setActualite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CommentaireActualite[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(CommentaireActualite $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setActualite($this);
        }

        return $this;
    }

    public function removeCommentaire(CommentaireActualite $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getActualite() === $this) {
                $commentaire->setActualite(null);
            }
        }

        return $this;
    }
}
