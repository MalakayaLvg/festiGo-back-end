<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;


class RegistrationController extends AbstractController
{

    #[Route('/api/register', name: 'app_register', methods: 'POST')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SerializerInterface $serializer, UserRepository $userRepository, SendEmailService $emailService): Response
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        $userExists = $userRepository->findOneBy(["email" => $user->getEmail()]);
        if ($userExists) {
            return $this->json("Email deja utilise", 400);
        }

        /** @var string $plainPassword */
        $plainPassword = $user->getPassword();
        $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

        // Générer le token de vérification
        $verificationToken = bin2hex(random_bytes(32));
        $user->setVerificationToken($verificationToken);
        $user->setVerificationTokenExpiresAt(new \DateTime('+24 hours'));
        $user->setIsVerified(false); // L'utilisateur n'est pas encore vérifié

        $entityManager->persist($user);
        $entityManager->flush();

        // Envoyer l'email avec le lien de vérification
        $verificationUrl = $this->generateUrl('app_verify_email', [
            'token' => $verificationToken
        ], \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL);

        $emailService->send(
            'noreply@festigo.com',
            $user->getEmail(),
            'Activation de votre compte',
            'register',
            compact('user', 'verificationUrl')
        );

        return $this->json([
            'message' => 'Inscription réussie ! Un email vous a été envoyé pour confirmer votre compte.'
        ], 201);
    }

    #[Route('/api/verify-email/{token}', name: 'app_verify_email', methods: ['GET'])]
    public function verifyEmail(string $token, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->findOneBy(['verificationToken' => $token]);

        if (!$user) {
            return $this->json(['message' => 'Token de vérification invalide'], 400);
        }

        if ($user->getVerificationTokenExpiresAt() < new \DateTime()) {
            return $this->json(['message' => 'Token de vérification expiré'], 400);
        }

        if ($user->isVerified()) {
            return $this->json(['message' => 'Compte déjà vérifié'], 200);
        }

        // Activer le compte
        $user->setIsVerified(true);
        $user->setVerificationToken(null);
        $user->setVerificationTokenExpiresAt(null);

        $entityManager->flush();

        return $this->json(['message' => 'Compte vérifié avec succès ! Vous pouvez maintenant vous connecter.'], 200);
    }

    #[Route('/api/login_check', name: 'api_login_check', methods: ['POST'])]
    public function login(): void
    {

    }


}
