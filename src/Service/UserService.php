<?php

namespace App\Service;

use App\Entity\Employee;
use App\Entity\User;
use App\Entity\Visitor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserService
{
    public function __construct(private EntityManagerInterface $manager)
    {}

    public function promoteToVisitor(User $user): Visitor
    {

        $visitor = new Visitor();
        $visitor->setEmail($user->getEmail());
        $visitor->setPassword($user->getPassword());
        $visitor->setRoles(["ROLE_VISITOR"]);
        $visitor->setIsVerified(true);

        $this->manager->remove($user);
        $this->manager->flush();

        $this->manager->persist($visitor);
        $this->manager->flush();

        return $visitor;
    }

    #[isGranted('ROLE_ADMIN')]
    public function promoteVisitorToEmployee(Visitor $visitor): Employee
    {
        $employee = new Employee();
        $employee->setEmail($visitor->getEmail());
        $employee->setPassword($visitor->getPassword());
        $employee->setRoles(["ROLE_EMPLOYEE"]);
        $employee->setCreditsBalance($visitor->getCreditsBalance());
        $employee->setIsVerified(true);

        $this->manager->remove($visitor);
        $this->manager->flush();

        $this->manager->persist($employee);
        $this->manager->flush();

        return $employee;
    }


}
