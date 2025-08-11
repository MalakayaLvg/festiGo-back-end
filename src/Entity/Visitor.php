<?php

namespace App\Entity;

use App\Repository\VisitorRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: VisitorRepository::class)]
class Visitor extends User
{
    #[ORM\Column]
    #[Groups('user:detail')]
    private ?int $creditsBalance = null;

    public function __construct()
    {
        $this->creditsBalance = 0;
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
}
