<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['ticket-detail'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['ticket-detail'])]
    private ?string $qrCode = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['ticket-detail'])]
    private ?\DateTime $purshaseDate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['ticket-detail'])]
    private ?string $price = null;

    #[ORM\Column]
    #[Groups(['ticket-detail'])]
    private ?bool $isValidate = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[Groups(['ticket-detail'])]
    private ?Visitor $visitor = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['ticket-detail'])]
    private ?Festival $festival = null;

    #[ORM\Column]
    #[Groups(['ticket-detail'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['ticket-detail'])]
    private ?bool $isPurshase = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQrCode(): ?string
    {
        return $this->qrCode;
    }

    public function setQrCode(?string $qrCode): static
    {
        $this->qrCode = $qrCode;

        return $this;
    }

    public function getPurshaseDate(): ?\DateTime
    {
        return $this->purshaseDate;
    }

    public function setPurshaseDate(\DateTime $purshaseDate): static
    {
        $this->purshaseDate = $purshaseDate;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price !== null ? (float) $this->price : null;
    }

    public function setPrice(float $price): static
    {
        $this->price = number_format(round($price, 2), 2, '.', '');

        return $this;
    }

    public function isValidate(): ?bool
    {
        return $this->isValidate;
    }

    public function setIsValidate(bool $isValidate): static
    {
        $this->isValidate = $isValidate;

        return $this;
    }

    public function getVisitor(): ?Visitor
    {
        return $this->visitor;
    }

    public function setVisitor(?Visitor $visitor): static
    {
        $this->visitor = $visitor;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isPurshase(): ?bool
    {
        return $this->isPurshase;
    }

    public function setIsPurshase(bool $isPurshase): static
    {
        $this->isPurshase = $isPurshase;

        return $this;
    }
}
