<?php

namespace App\Controller\Admin;

use App\Entity\Festival;
use App\Entity\Scene;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/admin')]
final class festivalAdminController extends AbstractController
{

    #[Route('/festival/create', name: 'app_admin_festival_create', methods: 'POST')]
    public function createFestival(SerializerInterface $serializer, Request $request, EntityManagerInterface $manager): Response
    {
        $festival = $serializer->deserialize($request->getContent(),Festival::class,'json');

        $manager->persist($festival);
        $manager->flush();

        return $this->json(["message"=>"Festival cree",$festival],201,[],['groups'=>'festival-detail']);
    }

    #[Route('/festival/edit/{id}', name: 'app_admin_festival_edit', methods: 'PUT')]
    public function editFestival(Festival $festival,Request $request, SerializerInterface $serializer, EntityManagerInterface $manager): Response
    {
        $serializer->deserialize($request->getContent(),Festival::class,'json',['object_to_populate'=>$festival]);
        $manager->flush();

        return $this->json(['message'=>"Festival edite",$festival],200,[],['groups'=>'festival-detail']);
    }

    #[Route('/festival/delete/{id}', name: 'app_admin_festival_delete', methods: 'DELETE')]
    public function deleteFestival(Festival $festival, EntityManagerInterface $manager): Response
    {
        $manager->remove($festival);
        $manager->flush();

        return $this->json(['message'=>'Festival supprime avec succes'],200);
    }

    #[Route('/festival/{id}/add/scene', name: 'app_admin_festival_add_scene', methods: 'POST')]
    public function addSceneToFestival(Festival $festival, SerializerInterface $serializer, Request $request, EntityManagerInterface $manager): Response
    {
        $scene = $serializer->deserialize($request->getContent(),Scene::class,'json');
        $scene->setFestival($festival);
        $manager->persist($scene);
        $manager->flush();

        return $this->json(['message'=>'Scene ajoute au festival',$scene],201,[],['groups'=>'scene-detail']);
    }

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
}
