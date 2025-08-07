<?php

namespace App\Controller;

use App\Repository\ArtistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class ArtistController extends AbstractController
{
    #[Route('/artist/all', name: 'app_admin_artist_all', methods: 'GET')]
    public function artistAll(ArtistRepository $artistRepository): Response
    {
        return $this->json($artistRepository->findAll(),200,[],['groups'=>'artist-detail']);
    }
}
