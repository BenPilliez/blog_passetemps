<?php

namespace App\Entity;

use App\Repository\RatingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RatingRepository::class)
 */
class Rating
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rates;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbRates;

    public function __toString()
    {
        return strval($this->rates);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRates(): ?float
    {
        return $this->rates;
    }

    public function getCalculateRate(): ?float
    {
        return $this->rates / $this->nbRates;
    }

    public function setRates(?float $rates): self
    {
        $this->rates = $rates;

        return $this;
    }

    public function getNbRates(): ?int
    {
        return $this->nbRates;
    }

    public function setNbRates(int $nbRates): self
    {
        $this->nbRates = $nbRates;

        return $this;
    }
}
