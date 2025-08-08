<?php

namespace App\Controller\Admin;

use App\Entity\Artist;
use App\Entity\Genre;
use App\Repository\GenreRepository;
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

    ///////////////////////////// GENRE /////////////////////////////

    #[Route('/artist/{id}/add/genre', name: 'app_admin_artist_add_genre', methods: 'POST')]
    public function addGenresFromArtist(Artist $artist,GenreRepository $genreRepository,Request $request, EntityManagerInterface $manager): Response
    {
        $data = json_decode($request->getContent(),true);
        $artist->addGenre($genreRepository->find($data['genreId']));

        $manager->persist($artist);
        $manager->flush();

        return $this->json(['message'=>'Genre ajouté a l\'Artiste',$artist],200,[],['groups'=>'artist-detail']);
    }

    #[Route('/artist/{id}/remove/genre', name: 'app_admin_artist_remove_genre', methods: 'POST')]
    public function removeGenreFromArtist(Artist $artist,GenreRepository $genreRepository,Request $request, SerializerInterface $serializer, EntityManagerInterface $manager): Response
    {
        $data = json_decode($request->getContent(),true);
        $artist->removeGenre($genreRepository->find($data['genreId']));

        $manager->persist($artist);
        $manager->flush();

        return $this->json(['message'=>'Genre supprime sur l\'artiste',$artist],200,[],['groups'=>'artist-detail']);
    }

    #[Route('/genre/create', name: 'app_admin_genre_create', methods: 'POST')]
    public function createGenre(SerializerInterface $serializer, Request $request, EntityManagerInterface $manager): Response
    {
        $genre = $serializer->deserialize($request->getContent(),Genre::class,'json');
        $manager->persist($genre);
        $manager->flush();

        return $this->json(['message'=>'Genre cree',$genre],200,[],['groups'=>'genre-detail']);
    }

    #[Route('/genre/edit/{id}', name: 'app_admin_genre_edit', methods: 'PUT')]
    public function editGenre(Genre $genre,SerializerInterface $serializer,Request $request, EntityManagerInterface $manager): Response
    {
        $serializer->deserialize($request->getContent(),Genre::class,'json',['object_to_populate'=>$genre]);
        $manager->flush();

        return $this->json(['message'=>'Genre edite',$genre],200,[],['groups'=>'genre-detail']);
    }

    #[Route('/genre/delete/{id}', name: 'app_admin_genre_delete', methods: 'DELETE')]
    public function deleteGenre(Genre $genre, EntityManagerInterface $manager): Response
    {
        $manager->remove($genre);
        $manager->flush();

        return $this->json(['message'=>'Genre supprime'],200);
    }

}
