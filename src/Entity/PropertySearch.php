<?php

namespace App\Entity;

/**
 * Class PropertySearch
 * @package App\Entity
 */
class PropertySearch
{
    /**
     * @var int|null
     */
    private $numAppart;

    /**
     * @var int|null
     */
    private $residence;

    /**
     * @return int|null
     */
    public function getNumAppart(): ?int
    {
        return $this->numAppart;
    }

    /**
     * @param int|null $numAppart
     */
    public function setNumAppart(?int $numAppart): void
    {
        $this->numAppart = $numAppart;
    }

    /**
     * @return int|null
     */
    public function getResidence(): ?int
    {
        return $this->residence;
    }

    /**
     * @param int|null $residence
     */
    public function setResidence(?int $residence): void
    {
        $this->residence = $residence;
    }


}