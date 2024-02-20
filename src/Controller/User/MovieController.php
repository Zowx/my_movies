<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\NewMovieType;
use App\Manager\MovieManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MovieController extends AbstractController
{

    #[Route('/movie', name: 'app_movie')]
    public function index(Request $request, MovieManager $movieManager): Response
    {
        $movie = new Movie();
        $form = $this->createForm(NewMovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $movieManager->new($form, $user, $movie);
            return $this->redirectToRoute('app_movie');
        }

        return $this->render('movie/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
