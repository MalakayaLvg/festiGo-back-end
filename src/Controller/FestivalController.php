<?php

namespace App\Controller;

use App\Entity\Festival;
use App\Repository\FestivalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class FestivalController extends AbstractController
{
    #[Route('/festival/all', name: 'app_festival_all', methods: 'GET')]
    public function allFestival(FestivalRepository $festivalRepository): Response
    {
        $festivals = $festivalRepository->findAll();
        return $this->json($festivals,200,[],['groups'=>'festival-detail']);
    }

    #[Route('festival/show/{id}', name: 'app_festival_show', methods: 'GET')]
    public function showFestival(Festival $festival): Response
    {
        return $this->json($festival,200,[],['groups'=>'festival-detail']);
    }
}
