<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class TicketController extends AbstractController
{
    #[Route('/ticket/all', name: 'app_ticket_all', methods: 'GET')]
    public function ticketAll(TicketRepository $ticketRepository): Response
    {
        return $this->json(['data'=>$ticketRepository->findAll()],200,[],['groups'=>'ticket-detail']);
    }

    #[Route('/ticket/available/all', name: 'app_ticket_available_all', methods: 'GET')]
    public function ticketAvailableAll(TicketRepository $ticketRepository): Response
    {
        $tickets = $ticketRepository->findBy(['isPurshase'=>false]);

        return $this->json(['data'=>$tickets],200,[],['groups'=>'ticket-detail']);
    }

    #[Route('/ticket/show/{id}', name: 'app_ticket_show', methods: 'GET')]
    public function showTicket(Ticket $ticket): Response
    {
        return $this->json(['data'=>$ticket],200,[],['groups'=>'ticket-detail']);
    }

    #[Route('/ticket/purchase/{id}', name: 'app_ticket_purchase', methods: 'POST')]
    public function buyTicket(Ticket $ticket,EntityManagerInterface $manager): Response
    {
        if( $ticket->isPurshase() === true){ return $this->json(['error'=>'Ticket deja achete'],400); }

        $ticket->setVisitor($this->getUser());
        $ticket->setPurshaseDate(new \DateTime());
        $ticket->setIsPurshase(true);

        $manager->persist($ticket);
        $manager->flush();

        return $this->json(['message'=>'Ticket acheter avec success','data'=>$ticket],200,[],['groups'=>'ticket-detail']);
    }
}
