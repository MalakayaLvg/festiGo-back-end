<?php

namespace App\Controller;

use App\Repository\SlotRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class SlotController extends AbstractController
{
    #[Route('/slot/all', name: 'app_slot_all', methods: 'GET')]
    public function slotAll(SlotRepository $slotRepository): Response
    {
        return $this->json($slotRepository->findAll(),200,[],['groups'=>'slot-detail']);
    }
}
