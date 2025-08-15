<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['item-detail','stand-detail','order-detail','order-line-detail'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['item-detail','stand-detail','order-detail','order-line-detail'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['item-detail'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['item-detail','order-line-detail'])]
    private ?int $creditsPrice = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['item-detail'])]
    private ?Stand $stand = null;

    /**
     * @var Collection<int, Orderline>
     */
    #[ORM\OneToMany(targetEntity: Orderline::class, mappedBy: 'item')]
    private Collection $orderlines;

    public function __construct()
    {
        $this->orderlines = new ArrayCollection();
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

    public function getCreditsPrice(): ?int
    {
        return $this->creditsPrice;
    }

    public function setCreditsPrice(int $creditsPrice): static
    {
        $this->creditsPrice = $creditsPrice;

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
            $orderline->setItem($this);
        }

        return $this;
    }

    public function removeOrderline(Orderline $orderline): static
    {
        if ($this->orderlines->removeElement($orderline)) {
            // set the owning side to null (unless already changed)
            if ($orderline->getItem() === $this) {
                $orderline->setItem(null);
            }
        }

        return $this;
    }

}
