<?php

namespace App\Controller;

use App\Entity\CreditsPayment;
use App\Entity\Order;
use App\Repository\VisitorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class OrderController extends AbstractController
{
    #[Route('/order/{id}/pay', name: 'app_order_pay', methods: 'POST')]
    public function payOrder(Order $order, VisitorRepository $visitorRepository, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        if (!$user){ return $this->json(['error'=>'Utilisateur non identifie'], 401); }

        if ($order->getStatus() === 'payed'){ return $this->json(['message'=>'Commande deja paye']); }

        $visitor = $visitorRepository->find($user);

        if ($visitor->getCreditsBalance() <= $order->getTotalAmount()){
            return $this->json(['message'=>'pas assez de credit pour paye la commande'],400);
        }

        $payment = new CreditsPayment();
        $payment->setTheOrder($order);
        $payment->setCreditsAmount($order->getTotalAmount());
        $payment->setPaymentDate(new \DateTime());
        $payment->setVisitor($visitor);
        $payment->setIsConfirm(true);

        $newBalance = $visitor->getCreditsBalance() - $order->getTotalAmount();
        $visitor->setCreditsBalance($newBalance);
        $order->setStatus('payed');

        $manager->persist($payment);
        $manager->persist($visitor);
        $manager->persist($order);
        $manager->flush();

        return $this->json(['message'=>'Commande paye avec succes','data'=>$order],200,[],['groups'=>'order-detail']);
    }
}
