<?php

namespace App\Entity;

use App\Repository\SlotRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: SlotRepository::class)]
class Slot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['slot-detail','festival-detail','artist-detail'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['slot-detail','festival-detail','artist-detail'])]
    private ?\DateTime $startTime = null;

    #[ORM\Column]
    #[Groups(['slot-detail','festival-detail','artist-detail'])]
    private ?\DateTime $endTime = null;

    #[ORM\ManyToOne(inversedBy: 'slots')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['slot-detail','artist-detail'])]
    private ?Scene $scene = null;

    #[ORM\ManyToOne(inversedBy: 'slots')]
    #[Groups(['slot-detail'])]
    private ?Artist $artist = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartTime(): ?\DateTime
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTime $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTime
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTime $endTime): static
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getScene(): ?Scene
    {
        return $this->scene;
    }

    public function setScene(?Scene $scene): static
    {
        $this->scene = $scene;

        return $this;
    }

    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    public function setArtist(?Artist $artist): static
    {
        $this->artist = $artist;

        return $this;
    }

    public function removeArtist(): static
    {
        $this->artist = null;

        return $this;
    }
}
