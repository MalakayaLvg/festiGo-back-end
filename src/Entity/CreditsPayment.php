<?php

namespace App\Entity;

use App\Repository\CreditsPaymentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CreditsPaymentRepository::class)]
class CreditsPayment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $creditsAmount = null;

    #[ORM\Column]
    private ?\DateTime $paymentDate = null;

    #[ORM\Column]
    private ?bool $isConfirm = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $theOrder = null;

    #[ORM\ManyToOne(inversedBy: 'creditsPayments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Visitor $visitor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreditsAmount(): ?int
    {
        return $this->creditsAmount;
    }

    public function setCreditsAmount(int $creditsAmount): static
    {
        $this->creditsAmount = $creditsAmount;

        return $this;
    }

    public function getPaymentDate(): ?\DateTime
    {
        return $this->paymentDate;
    }

    public function setPaymentDate(\DateTime $paymentDate): static
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    public function isConfirm(): ?bool
    {
        return $this->isConfirm;
    }

    public function setIsConfirm(bool $isConfirm): static
    {
        $this->isConfirm = $isConfirm;

        return $this;
    }

    public function getTheOrder(): ?Order
    {
        return $this->theOrder;
    }

    public function setTheOrder(Order $theOrder): static
    {
        $this->theOrder = $theOrder;

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
}
