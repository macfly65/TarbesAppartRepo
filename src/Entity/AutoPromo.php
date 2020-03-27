<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AutoPromoRepository")
 */
class AutoPromo
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
    private $titre;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $periode;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $date_d;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_f;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cible;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $statut;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $zone;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitre()
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getPeriode()
    {
        return $this->periode;
    }

    public function setPeriode(int $periode): self
    {
        $this->periode = $periode;

        return $this;
    }

    public function getDateD()
    {
        return $this->date_d;
    }

    public function setDateD($date_d): self
    {
        $this->date_d = $date_d;

        return $this;
    }

    public function getDateF()
    {
        return $this->date_f;
    }

    public function setDateF($date_f): self
    {
        $this->date_f = $date_f;

        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getCible()
    {
        return $this->cible;
    }

    public function setCible(string $cible): self
    {
        $this->cible = $cible;

        return $this;
    }

    public function getStatut()
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getZone()
    {
        return $this->zone;
    }

    public function setZone($zone): self
    {
        $this->zone = $zone;

        return $this;
    }
}
