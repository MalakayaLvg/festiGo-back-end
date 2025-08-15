<?php

namespace App\Entity;

use App\Repository\StandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: StandRepository::class)]
class Stand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['stand-detail','festival-detail','item-detail','order-detail'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['stand-detail','festival-detail','item-detail','order-detail'])]
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

    /**
     * @var Collection<int, Item>
     */
    #[ORM\OneToMany(targetEntity: Item::class, mappedBy: 'stand', orphanRemoval: true)]
    #[Groups(['stand-detail'])]
    private Collection $items;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'stand', orphanRemoval: true)]
    private Collection $orders;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Item>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setStand($this);
        }

        return $this;
    }

    public function removeItem(Item $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getStand() === $this) {
                $item->setStand(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setStand($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getStand() === $this) {
                $order->setStand(null);
            }
        }

        return $this;
    }
}
