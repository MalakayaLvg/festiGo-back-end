<?php

namespace App\Controller\Admin;

use App\Entity\Festival;
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

        return $this->json(["message"=>"Festival cree",$festival],201);
    }

    #[Route('/festival/edit/{id}', name: 'app_admin_festival_edit', methods: 'PUT')]
    public function editFestival(Festival $festival,Request $request, SerializerInterface $serializer, EntityManagerInterface $manager): Response
    {
        $serializer->deserialize($request->getContent(),Festival::class,'json',['object_to_populate'=>$festival]);
        $manager->flush();

        return $this->json(['message'=>"Festival edite",$festival],200);
    }

    #[Route('/festival/delete/{id}', name: 'app_admin_festival_delete', methods: 'DELETE')]
    public function deleteFestival(Festival $festival, EntityManagerInterface $manager): Response
    {
        $manager->remove($festival);
        $manager->flush();

        return $this->json(['message'=>'Festival supprime avec succes'],200);
    }
}
