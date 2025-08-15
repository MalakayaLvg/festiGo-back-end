<?php

namespace App\Entity;

use App\Repository\OrderlineRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: OrderlineRepository::class)]
class Orderline
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['order-detail','order-line-detail'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['order-detail','order-line-detail'])]
    private ?int $quantity = null;

    #[ORM\Column]
    #[Groups(['order-detail','order-line-detail'])]
    private ?int $unitPrice = null;

    #[ORM\ManyToOne(inversedBy: 'orderlines')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['order-line-detail'])]
    private ?Order $theOrder = null;

    #[ORM\ManyToOne(inversedBy: 'orderlines')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['order-detail','order-line-detail'])]
    private ?Item $item = null;

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

    public function getUnitPrice(): ?int
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(int $unitPrice): static
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    public function getTheOrder(): ?Order
    {
        return $this->theOrder;
    }

    public function setTheOrder(?Order $theOrder): static
    {
        $this->theOrder = $theOrder;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): static
    {
        $this->item = $item;

        return $this;
    }
}
