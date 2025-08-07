<?php

namespace App\Controller\Admin;

use App\Entity\Scene;
use App\Entity\Slot;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/admin')]
final class SceneAdminController extends AbstractController
{
    #[Route('/scene/delete/{id}', name: 'app_admin_scene_delete', methods: 'DELETE')]
    public function deleteScene(Scene $scene, EntityManagerInterface $manager): Response
    {
        $manager->remove($scene);
        $manager->flush();

        return $this->json(['message'=>'scene supprime'],200);
    }

    #[Route('/scene/edit/{id}', name: 'app_admin_scene_edit', methods: 'PUT')]
    public function editScene(Scene $scene, Request $request, SerializerInterface $serializer, EntityManagerInterface $manager): Response
    {
        $serializer->deserialize($request->getContent(),Scene::class,'json',['object_to_populate'=>$scene]);
        $manager->flush();

        return $this->json(['message'=>'scene edite',$scene],200,[],['groups'=>'scene-detail']);
    }

    #[Route('/scene/{id}/add/slot', name: 'app_admin_scene_add_slot', methods: 'POST')]
    public function addSlotToScene(Scene $scene,Request $request, SerializerInterface $serializer, EntityManagerInterface $manager): Response
    {
        $slot = $serializer->deserialize($request->getContent(),Slot::class,'json');
        $slot->setScene($scene);
        $manager->persist($slot);
        $manager->flush();

        return $this->json(['message'=>'Creneau ajoute',$slot],200,[],['groups'=>'slot-detail']);
    }

    #[Route('/slot/delete/{id}', name: 'app_admin_slot_delete', methods: 'DELETE')]
    public function deleteSlot(Slot $slot, EntityManagerInterface $manager): Response
    {
        $manager->remove($slot);
        $manager->flush();

        return $this->json(['message'=>'creneau supprime'],200);
    }

    #[Route('/slot/edit/{id}', name: 'app_admin_slot_edit', methods: 'PUT')]
    public function editSlot(Scene $slot, Request $request, SerializerInterface $serializer, EntityManagerInterface $manager): Response
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



}
