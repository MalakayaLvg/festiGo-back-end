<?php

namespace App\Controller\Admin;

use App\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/admin')]
final class ItemAdminController extends AbstractController
{
    #[Route('/item/delete/{id}', name: 'app_admin_item_delete', methods: 'DELETE')]
    public function deleteItem(Item $item, EntityManagerInterface $manager): Response
    {
        $manager->remove($item);
        $manager->flush();

        return $this->json(['message'=>'Item supprime'],200);
    }

    #[Route('/item/edit/{id}', name: 'app_admin_item_edit', methods: 'PUT')]
    public function editItem(Item $item, Request $request, SerializerInterface $serializer, EntityManagerInterface $manager): Response
    {
        $serializer->deserialize($request->getContent(),Item::class,'json',['object_to_populate']);
        $manager->flush();

        return $this->json(['message'=>'Item edite','data'=>$item],200,[],['groups'=>'item-detail']);
    }
}
