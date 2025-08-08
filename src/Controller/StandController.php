<?php

namespace App\Controller;

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
}
