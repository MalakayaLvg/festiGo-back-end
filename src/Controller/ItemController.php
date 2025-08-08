<?php

namespace App\Controller;

use App\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class ItemController extends AbstractController
{
    #[Route('/item/show/{id}', name: 'app_item_show', methods: 'GET')]
    public function showItem(Item $item): Response
    {
        return $this->json(['data'=>$item],200,[],['groups'=>'item-detail']);
    }
}
