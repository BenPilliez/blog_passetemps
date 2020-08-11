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
     * @ORM\Column(type="integer", nullable=true)
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

    public function getRates(): ?int
    {
        if (null !== $this->rates) {
            return $this->rates / $this->nbRates;
        }

        return $this->rates;
    }

    public function setRates(?int $rates): self
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
