<?php

namespace App\Entity;

use App\Repository\CreditsFormulaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CreditsFormulaRepository::class)]
class CreditsFormula
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['credits-formula-detail'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['credits-formula-detail'])]
    private ?int $quantity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['credits-formula-detail'])]
    private ?string $price = null;

    #[ORM\ManyToOne(inversedBy: 'creditsFormulas')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['credits-formula-detail'])]
    private ?Festival $festival = null;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

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
