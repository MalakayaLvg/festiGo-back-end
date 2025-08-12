<?php

namespace App\Controller\Admin;

use App\Entity\CreditsFormula;
use App\Entity\Festival;
use App\Entity\Ticket;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\False_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/admin')]
final class TicketAdminController extends AbstractController
{
    #[Route('/festival/{id}/add/tickets', name: 'app_admin_festival_add_tickets', methods: 'POST')]
    public function addTicketToFestival(Festival $festival, Request $request, EntityManagerInterface $manager): Response
    {
        $data = json_decode($request->getContent(),true);
        $quantity = $data['quantity'] ?? 1;
        $price = $data['price'] ?? 0.00;

        if ($quantity <= 0 || $quantity > 1000 ){
            return $this->json(['error'=>'La quantite doit etre entre 1 et 1000'],400);
        }

        $createdTickets = [];

        for ($i = 0;$i < $quantity; $i++){
            $ticket = new Ticket();
            $ticket->setFestival($festival);
            $ticket->setPrice($price);
            $ticket->setCreatedAt(new \DateTimeImmutable());
            $ticket->setIsValidate(false);
            $ticket->setIsPurshase(false);

            $manager->persist($ticket);
            $createdTickets[] = $ticket;
        }

        $manager->flush();

        return $this->json([
            'message'=> sprintf('%d tickets generes pour le festival',$quantity),
            'ticketCreated' => count($createdTickets)
            ],201);
    }



}
