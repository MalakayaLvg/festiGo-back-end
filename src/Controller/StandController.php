<?php

namespace App\Controller;

use App\Entity\Stand;
use App\Repository\StandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class StandController extends AbstractController
{
    #[Route('/stand/all', name: 'app_stand_all', methods: 'GET')]
    public function index(StandRepository $standRepository): Response
    {
        return $this->json($standRepository->findAll(),200,[],['groups'=>'stand-detail']);
    }

    #[Route('/stand/show/{id}', name: 'app_stand_show', methods: 'GET')]
    public function showStand(Stand $stand): Response
    {
        return $this->json(['data'=>$stand],200,[],['groups'=>'stand-detail']);
    }

    #[Route('/stand/{id}/show/items', name: 'app_stand_show_items', methods: 'GET')]
    public function showStandItems(Stand $stand): Response
    {
        return $this->json(['data'=>$stand->getItems()],200,[],['groups'=>'item-detail']);
    }
}
