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

}
