<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee extends Visitor
{
    public function __construct()
    {
        parent::__construct();
        $this->test = 'feur';
        $this->orders = new ArrayCollection();
    }

    #[ORM\Column(length: 255)]
    private ?string $test = null;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'employee', orphanRemoval: true)]
    private Collection $orders;

    public function getTest(): ?string
    {
        return $this->test;
    }

    public function setTest(string $test): static
    {
        $this->test = $test;

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
            $order->setEmployee($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getEmployee() === $this) {
                $order->setEmployee(null);
            }
        }

        return $this;
    }
}
