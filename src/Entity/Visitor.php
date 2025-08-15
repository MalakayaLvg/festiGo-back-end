<?php

namespace App\Entity;

use App\Repository\VisitorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: VisitorRepository::class)]
class Visitor extends User
{
    #[ORM\Column]
    #[Groups('user:detail')]
    private ?int $creditsBalance = null;

    /**
     * @var Collection<int, Ticket>
     */
    #[ORM\OneToMany(targetEntity: Ticket::class, mappedBy: 'visitor')]
    private Collection $tickets;

    /**
     * @var Collection<int, CreditsPayment>
     */
    #[ORM\OneToMany(targetEntity: CreditsPayment::class, mappedBy: 'visitor')]
    private Collection $creditsPayments;

    public function __construct()
    {
        $this->creditsBalance = 0;
        $this->tickets = new ArrayCollection();
        $this->creditsPayments = new ArrayCollection();
    }

    public function getCreditsBalance(): ?int
    {
        return $this->creditsBalance;
    }

    public function setCreditsBalance(int $creditsBalance): static
    {
        $this->creditsBalance = $creditsBalance;

        return $this;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): static
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
            $ticket->setVisitor($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): static
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getVisitor() === $this) {
                $ticket->setVisitor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CreditsPayment>
     */
    public function getCreditsPayments(): Collection
    {
        return $this->creditsPayments;
    }

    public function addCreditsPayment(CreditsPayment $creditsPayment): static
    {
        if (!$this->creditsPayments->contains($creditsPayment)) {
            $this->creditsPayments->add($creditsPayment);
            $creditsPayment->setVisitor($this);
        }

        return $this;
    }

    public function removeCreditsPayment(CreditsPayment $creditsPayment): static
    {
        if ($this->creditsPayments->removeElement($creditsPayment)) {
            // set the owning side to null (unless already changed)
            if ($creditsPayment->getVisitor() === $this) {
                $creditsPayment->setVisitor(null);
            }
        }

        return $this;
    }
}
