<?php

namespace App\Controller;

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
}
