<?php

namespace App\Controller;

use App\Repository\SceneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class SceneController extends AbstractController
{
    #[Route('/scene/all', name: 'app_scene_all', methods: 'GET')]
    public function sceneAll(SceneRepository $sceneRepository): Response
    {
        return $this->json($sceneRepository->findAll(),200,[],['groups'=>'scene-detail']);
    }
}
