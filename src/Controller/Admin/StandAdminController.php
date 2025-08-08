<?php

namespace App\Controller\Admin;

use App\Entity\Item;
use App\Entity\Stand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/admin')]
final class StandAdminController extends AbstractController
{
    #[Route('/stand/edit/{id}', name: 'app_admin_stand_edit', methods: 'PUT')]
    public function editStand(Stand $stand, SerializerInterface $serializer, Request $request, EntityManagerInterface $manager): Response
    {
        $serializer->deserialize($request->getContent(),Stand::class,'json',['object_to_populate'=>$stand]);
        $manager->flush();

        return $this->json(['message'=>'Stand edite',$stand],200,[],['groups'=>'stand-detail']);
    }

    #[Route('/stand/delete/{id}', name: 'app_admin_stand_delete', methods: 'DELETE')]
    public function deleteStand(Stand $stand, EntityManagerInterface $manager): Response
    {
        $manager->remove($stand);
        $manager->flush();

        return $this->json(['message'=>'Stand supprime'],200);
    }

    #[Route('/stand/{id}/add/item', name: 'app_admin_stand_add_item', methods: 'POST')]
    public function addItemToStand(Stand $stand,Request $request, SerializerInterface $serializer, EntityManagerInterface $manager): Response
    {
        $item = $serializer->deserialize($request->getContent(),Item::class,'json');
        $item->setStand($stand);
        $manager->persist($item);
        $manager->flush();

        return $this->json(['message'=>'Item ajoute au stand',$item],201,[],['groups'=>'item-detail']);
    }



}
