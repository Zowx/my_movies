<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiMovieController extends AbstractController
{
    #[Route('/api/movie', name: 'app_api_movie')]
    public function index(): Response
    {
        return $this->render('api_movie/index.html.twig', [
            'controller_name' => 'ApiMovieController',
        ]);
    }
}
