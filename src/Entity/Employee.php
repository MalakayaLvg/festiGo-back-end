<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee extends Visitor
{
    public function __construct()
    {
        parent::__construct();
        $this->test = 'feur';
    }

    #[ORM\Column(length: 255)]
    private ?string $test = null;

    public function getTest(): ?string
    {
        return $this->test;
    }

    public function setTest(string $test): static
    {
        $this->test = $test;

        return $this;
    }
}
