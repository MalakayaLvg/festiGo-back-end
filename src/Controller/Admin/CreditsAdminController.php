<?php

namespace App\Controller\Admin;

use App\Entity\CreditsFormula;
use App\Entity\Festival;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/admin')]
final class CreditsAdminController extends AbstractController
{
    #[Route('/festival/{id}/add/credits-formula', name: 'app_admin_festival_add_credits-formula', methods: 'POST')]
    public function addCreditsFormulaToFestival(Festival $festival, Request $request, EntityManagerInterface $manager): Response
    {
        $data = json_decode($request->getContent(),true);
        $price = $data['price'] ?? 0.00;
        $quantity = $data['quantity'] ?? 1;

        $creditsFormula = new CreditsFormula();
        $creditsFormula->setFestival($festival);
        $creditsFormula->setPrice($price);
        $creditsFormula->setQuantity($quantity);

        $manager->persist($creditsFormula);
        $manager->flush();

        return $this->json(['message'=>'Formule ajoute au festival !','data'=>$creditsFormula],200,[],['groups'=>'credits-formula-detail']);
    }

    #[Route('/credits-formula/edit/{id}', name: 'app_admin_credits-formula_edit', methods: 'PUT')]
    public function editCreditsFormula(CreditsFormula $creditsFormula, Request $request, EntityManagerInterface $manager): Response
    {
        $data = json_decode($request->getContent(), true);
        $creditsFormula->setPrice($data['price']);
        $creditsFormula->setQuantity($data['quantity']);
        $manager->persist($creditsFormula);
        $manager->flush();

        return $this->json(['message'=>'Formule edite avec succes','data'=>$creditsFormula],200,[],['groups'=>'credits-formula-detail']);
    }

    #[Route('/credits-formula/delete/{id}', name: 'app_admin_credits-formula_edit', methods: 'DELETE')]
    public function deleteCreditsFormula(CreditsFormula $creditsFormula, EntityManagerInterface $manager): Response
    {
        $manager->remove($creditsFormula);
        $manager->flush();

        return $this->json(['message'=>'Formule supprime avec succes'],200);
    }
}
