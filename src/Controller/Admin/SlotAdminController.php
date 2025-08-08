<?php

namespace App\Controller\Admin;

use App\Entity\Artist;
use App\Entity\Scene;
use App\Entity\Slot;
use App\Repository\ArtistRepository;
use App\Repository\SlotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/admin')]
final class SlotAdminController extends AbstractController
{
    #[Route('/slot/delete/{id}', name: 'app_admin_slot_delete', methods: 'DELETE')]
    public function deleteSlot(Slot $slot, EntityManagerInterface $manager): Response
    {
        $manager->remove($slot);
        $manager->flush();

        return $this->json(['message'=>'creneau supprime'],200);
    }

    #[Route('/slot/edit/{id}', name: 'app_admin_slot_edit', methods: 'PUT')]
    public function editSlot(Slot $slot, Request $request, SerializerInterface $serializer, EntityManagerInterface $manager): Response
    {
        $serializer->deserialize($request->getContent(),Scene::class,'json',['object_to_populate'=>$slot]);
        $manager->flush();

        return $this->json(['message'=>'creneau edite',$slot],200,[],['groups'=>'slot-detail']);
    }

    #[Route('/slot/{id}/add/artist', name: 'app_admin_slot_add_artist', methods: 'POST')]
    public function addArtistToSlot(Slot $slot, Request $request, EntityManagerInterface $manager, ArtistRepository $artistRepository): Response
    {
        $data = json_decode($request->getContent(),true);
        $slot->setArtist($artistRepository->find($data['artistId']));

        $manager->persist($slot);
        $manager->flush();

        return $this->json(['message'=>'Artist bien ajouté au creneau',$slot],200,[],['groups'=>'slot-detail']);
    }

    #[Route('/slot/{id}/remove/artist', name: 'app_admin_slot_remove_artist', methods: 'PUT')]
    public function removeArtistToSlot(Slot $slot,EntityManagerInterface $manager): Response
    {
        $slot->removeArtist();
        $manager->flush();

        return $this->json(['message'=>'Artiste enleve du creneau',$slot],200,[],['groups'=>'slot-detail']);
    }
}
