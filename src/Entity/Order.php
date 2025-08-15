<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['order-detail','order-line-detail'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['order-detail'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['order-detail'])]
    private ?int $totalAmount = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['order-detail'])]
    private ?string $qrCodePayment = null;

    #[ORM\Column(length: 255)]
    #[Groups(['order-detail'])]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['order-detail'])]
    private ?Employee $employee = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['order-detail'])]
    private ?Stand $stand = null;

    /**
     * @var Collection<int, Orderline>
     */
    #[ORM\OneToMany(targetEntity: Orderline::class, mappedBy: 'theOrder', orphanRemoval: true)]
    #[Groups(['order-detail'])]
    private Collection $orderlines;

    public function __construct()
    {
        $this->orderlines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTotalAmount(): ?int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(?int $totalAmount): static
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getQrCodePayment(): ?string
    {
        return $this->qrCodePayment;
    }

    public function setQrCodePayment(?string $qrCodePayment): static
    {
        $this->qrCodePayment = $qrCodePayment;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): static
    {
        $this->employee = $employee;

        return $this;
    }

    public function getStand(): ?Stand
    {
        return $this->stand;
    }

    public function setStand(?Stand $stand): static
    {
        $this->stand = $stand;

        return $this;
    }

    /**
     * @return Collection<int, Orderline>
     */
    public function getOrderlines(): Collection
    {
        return $this->orderlines;
    }

    public function addOrderline(Orderline $orderline): static
    {
        if (!$this->orderlines->contains($orderline)) {
            $this->orderlines->add($orderline);
            $orderline->setTheOrder($this);
        }

        return $this;
    }

    public function removeOrderline(Orderline $orderline): static
    {
        if ($this->orderlines->removeElement($orderline)) {
            // set the owning side to null (unless already changed)
            if ($orderline->getTheOrder() === $this) {
                $orderline->setTheOrder(null);
            }
        }

        return $this;
    }
}
