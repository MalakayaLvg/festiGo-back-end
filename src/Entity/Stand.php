<?php

namespace App\Entity;

use App\Repository\StandRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: StandRepository::class)]
class Stand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['stand-detail','festival-detail'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['stand-detail','festival-detail'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['stand-detail'])]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['stand-detail'])]
    private ?string $gpsCoordinates = null;

    #[ORM\ManyToOne(inversedBy: 'stands')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['stand-detail'])]
    private ?Festival $festival = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getGpsCoordinates(): ?string
    {
        return $this->gpsCoordinates;
    }

    public function setGpsCoordinates(?string $gpsCoordinates): static
    {
        $this->gpsCoordinates = $gpsCoordinates;

        return $this;
    }

    public function getFestival(): ?Festival
    {
        return $this->festival;
    }

    public function setFestival(?Festival $festival): static
    {
        $this->festival = $festival;

        return $this;
    }
}
