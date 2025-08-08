<?php

namespace App\Controller\Admin;

use App\Entity\Artist;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/admin')]
final class ArtistAdminController extends AbstractController
{

    #[Route('/artist/create', name: 'app_admin_artist_create')]
    public function create(SerializerInterface $serializer, Request $request, EntityManagerInterface $manager): Response
    {
        $artist = $serializer->deserialize($request->getContent(),Artist::class,'json');
        $manager->persist($artist);
        $manager->flush();

        return $this->json(['message'=>'Artiste cree',$artist],201,[],['groups'=>'artist-detail']);
    }

    #[Route('/artist/edit/{id}', name: 'app_admin_artist_edit', methods: 'PUT')]
    public function editArtist(Artist $artist,Request $request,SerializerInterface $serializer,EntityManagerInterface $manager): Response
    {
        $serializer->deserialize($request->getContent(),Artist::class,'json',['object_to_populate'=>$artist]);
        $manager->flush();

        return $this->json(['message'=>'Artiste edite',$artist],200,[],['groups'=>'artist-detail']);
    }

    #[Route('/artist/delete/{id}', name: 'app_admin_artist_delete', methods: 'DELETE')]
    public function deleteArtist(Artist $artist, EntityManagerInterface $manager): Response
    {
        $manager->remove($artist);
        $manager->flush();

        return $this->json(['message'=>'Artiste supprime'],200);
    }

}
