<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;


class AuthController extends AbstractController
{

    #[Route('/api/register', name: 'app_register', methods: 'POST')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SerializerInterface $serializer, UserRepository $userRepository): Response
    {
        $user = $serializer->deserialize($request->getContent(),User::class, 'json');

        $userExists = $userRepository->findOneBy(["email"=>$user->getEmail()]);
        if ($userExists) {
            return $this->json("email already exists", 300);
        }
        /** @var string $plainPassword */
        $plainPassword = $user->getPassword();

        $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json($user, 201, [], ['groups'=>['user:detail']]);

    }

    #[Route('/api/login_check', name: 'api_login_check', methods: ['POST'])]
    public function login(): void
    {

    }


}
