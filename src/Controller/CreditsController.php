<?php

namespace App\Controller;

use App\Entity\CreditsFormula;
use App\Repository\CreditsFormulaRepository;
use App\Repository\VisitorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class CreditsController extends AbstractController
{
    #[Route('/credits-formula/all', name: 'app_credits-formula_all', methods: 'GET')]
    public function creditsFormulaAll(CreditsFormulaRepository $creditsFormulaRepository): Response
    {
        return $this->json(['data'=>$creditsFormulaRepository->findAll()],200,[],['groups'=>'credits-formula-detail']);
    }

    #[Route('/credits-formula/show/{id}', name: 'app_credits-formula_show', methods: 'GET')]
    public function showCreditsFormula(CreditsFormula $creditsFormula): Response
    {
        return $this->json(['data'=>$creditsFormula],200,[],['groups'=>'credits-formula-detail']);
    }

    #[Route('/credits-formula/buy/{id}')]
    public function buyCreditsFormula(CreditsFormula $creditsFormula,VisitorRepository $visitorRepository,EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        if (!$user){
            return $this->json(['error'=>'Utilisateur non identifie'],401);
        }
        $visitor = $visitorRepository->find($user);
        $newBalance = $visitor->getCreditsBalance() + $creditsFormula->getQuantity();
        $visitor->setCreditsBalance($newBalance);
        $manager->persist($visitor);
        $manager->flush();

        return $this->json(['message'=>'Achat bien effectue','data'=>$visitor],200,[],['groups'=>'user:detail']);
    }
}
