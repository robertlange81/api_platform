<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\CheeseListingRepository")
 */
class CheeseListing
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
    private $CheeseListing2;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCheeseListing2(): ?string
    {
        return $this->CheeseListing2;
    }

    public function setCheeseListing2(?string $CheeseListing2): self
    {
        $this->CheeseListing2 = $CheeseListing2;

        return $this;
    }
}
