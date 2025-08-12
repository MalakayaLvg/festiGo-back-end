<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Repository\VisitorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class VisitorController extends AbstractController
{
    #[Route('/visitor/show/ticket/{id}/qrcode', name: 'app_visitor_show_ticket_qrcode', methods: 'GET')]
    public function showTicketQrCOde(Ticket $ticket, VisitorRepository $visitorRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Utilisateur non authentifie'], 401);
        }
        $visitor = $visitorRepository->find($user);
        if ($ticket->getVisitor() !== $visitor){
            return $this->json(['error' => 'Vous devez etre le proprietaire du ticket pour le voir'], 400);
        }

        return $this->json(['data'=>$ticket->getQrCode()],200);
    }

    #[Route('/visitor/show/credits-balance', name: 'app_visitor_show_credit-balance', methods: 'GET')]
    public function showCreditsBalance(VisitorRepository $visitorRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Utilisateur non authentifie'], 401);
        }

        $visitor = $visitorRepository->find($user);
        $creditsBalance = $visitor->getCreditsBalance();

        return $this->json(['data'=>$creditsBalance],200);
    }

    #[Route('/visitor/show/tickets', name: 'app_visitor_show_tickets', methods: 'GET')]
    public function showTickets(VisitorRepository $visitorRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Utilisateur non authentifie'], 401);
        }

        $visitor = $visitorRepository->find($user);
        $tickets = $visitor->getTickets();

        return $this->json(['data'=>$tickets],200,[],['groups'=>'ticket-detail']);
    }

    #[Route('/visitor/show/ticket/{id}', name: 'app_visitor_show_ticket', methods: 'GET')]
    public function showTicket(Ticket $ticket,VisitorRepository $visitorRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Utilisateur non authentifie'], 401);
        }
        $visitor = $visitorRepository->find($user);
        if ($ticket->getVisitor() !== $visitor){
            return $this->json(['error' => 'Vous devez etre le proprietaire du ticket pour le voir'], 400);
        }

        return $this->json(['data'=>$ticket],200,[],['groups'=>'ticket-detail']);
    }

}
