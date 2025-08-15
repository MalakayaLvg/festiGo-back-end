<?php

namespace App\Controller\Employee;

use App\Entity\Order;
use App\Entity\Orderline;
use App\Entity\Stand;
use App\Repository\EmployeeRepository;
use App\Repository\ItemRepository;
use App\Service\QrCodeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/employee')]
final class OrderEmployeeController extends AbstractController
{
    #[Route('/stand/{id}/order/create', name: 'app_employee_stand_order_create', methods: 'POST')]
    public function createOrder(Stand $stand,EmployeeRepository $employeeRepository, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        if(!$user){return $this->json(['error'=>'utilisateur non identifie'],401);}

        $employee = $employeeRepository->find($user);

        $order = new Order();
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setTotalAmount(0);
        $order->setStatus('created');
        $order->setEmployee($employee);
        $order->setStand($stand);

        $manager->persist($order);
        $manager->flush();

        return $this->json(['message'=>'Commande creer avec succes','data'=>$order],200,[],['groups'=>'order-detail']);
    }

    #[Route('/order/{id}/add/item', name: 'app_employee_order_add_item', methods: 'PUT')]
    public function addItemToOrder(Order $order, Request $request, EntityManagerInterface $manager, ItemRepository $itemRepository): Response
    {
        $data = json_decode($request->getContent(),true);
        $itemId = $data['itemId'];
        $quantity = $data['quantity'];
        $item = $itemRepository->find($itemId);

        $orderLine = new Orderline();
        $orderLine->setItem($item);
        $orderLine->setQuantity($quantity);
        $orderLine->setUnitPrice($item->getCreditsPrice());
        $orderLine->setTheOrder($order);

        $manager->persist($orderLine);
        $manager->flush();

        return $this->json(['message'=>'Item ajoute a la commande avec success','data'=>$order],200,[],['groups'=>'order-detail']);
    }

    #[Route('/order/show/{id}', name: 'app_employee_order_show', methods: 'GET')]
    public function showOrder(Order $order): Response
    {
        return $this->json(['data'=>$order],200,[],['groups'=>'order-detail']);
    }

    #[Route('/order/confirm/{id}', name: 'app_employee_order_confirm', methods: 'POST')]
    public function confirmOrder (Order $order,QrCodeService $qrCodeService, EntityManagerInterface $manager): Response
    {
        $items = $order->getOrderlines();
        $totalAmount = $order->getTotalAmount();
        foreach ($items as $item){
            $price = $item->getUnitPrice() * $item->getQuantity();
            $totalAmount += $price;
        }
        $order->setTotalAmount($totalAmount);
        $order->setStatus('confirm');

        $data = [
            'id'=>$order->getId(),
            'stand'=>$order->getStand()->getId(),
            'createdAt'=>$order->getCreatedAt(),
            'totalAmount'=>$totalAmount
        ];
        $qrcode = $qrCodeService->generateQrCode(json_encode($data));
        $order->setQrCodePayment($qrcode);

        $manager->persist($order);
        $manager->flush();

        return $this->json(['message'=>'Commande confirme','data'=>$order],200,[],['groups'=>'order-detail']);
    }

    #[Route('/orderline/edit/{id}', name: 'app_employee_edit_orderline', methods: 'PUT')]
    public function editOrderLine(Orderline $orderline, Request $request, EntityManagerInterface $manager): Response
    {
        $data = json_decode($request->getContent(),true);
        $quantity = $data['quantity'];
        $orderline->setQuantity($quantity);

        $manager->persist($orderline);
        $manager->flush();

        return $this->json(['message'=>'Ligne de la commande edite','data'=>$orderline],200,[],['groups'=>'order-line-detail']);
    }

    #[Route('/orderline/delete/{id}', name: 'app_employee_delete_orderline', methods: 'DELETE')]
    public function deleteOrderLine(Orderline $orderline, EntityManagerInterface $manager): Response
    {
        $manager->remove($orderline);
        $manager->flush();
        return $this->json(['message'=>'Ligne de commande supprime avec success'],200);
    }
}
