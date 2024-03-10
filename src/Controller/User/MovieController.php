<?php

namespace App\Controller\User;

use App\Entity\Movie;
use App\Entity\User;
use App\Form\EditMovieType;
use App\Form\NewMovieType;
use App\Manager\MovieManager;
use App\Repository\MovieRepository;
use App\Service\TmdbService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Environment;

#[Route("/movies")]
#[IsGranted("ROLE_USER")]
class MovieController extends AbstractController
{
    // created movie
    #[Route('/movie', name: 'app_movie')]
    public function index(
        Request $request,
        MovieManager $movieManager,
        MovieRepository $movieRepository
    ): Response
    {
        $movie = new Movie();
        $form = $this->createForm(NewMovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $movieManager->new($form, $user, $movie, $this->getParameter('movie'));
            $this->addFlash('success', 'Le film a bien été ajouté.');
            return $this->redirectToRoute('app_movie');
        }

        $user = $this->getUser();
        $movies = $movieRepository->findBy(['user' => $user]);

        return $this->render('movie/index.html.twig', [
            'form' => $form->createView(),
            'movies' => $movies,
        ]);
    }

    #[Route('/details/{id}', name: 'app_movie_details')]
    public function movieDetails(
        Movie $movie,
        MovieManager $movieManager,
        Request $request
    ): Response
    {
        $form = $this->createForm(EditMovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movieManager->edit($form, $movie, $request->get('id'));
            $this->addFlash('success', 'Le film a bien été modifié.');
            return $this->redirectToRoute('app_movie_details', [
                'id' => $movie->getId(),
            ]);
        }

        return $this->render('movie/details.html.twig', [
            'movie' => $movie,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/movie/see/{id}', name: 'app_movie_see')]
    public function changeSee(
        Movie $movie,
        MovieManager $movieManager
    ): RedirectResponse
    {
        $movieManager->changeSee($movie);
        $this->addFlash('success', 'Le statut du film a bien été modifié.');
        return $this->redirectToRoute('app_movie_details', [
            'id' => $movie->getId(),
        ]);
    }

    #[Route('/movie/delete/{id}', name: 'app_movie_delete')]
    public function deleteMovie(
        Movie $movie,
        MovieManager $movieManager
    ): RedirectResponse
    {
        $movieManager->deleteMovie($movie);
        $this->addFlash('success', 'Le film a bien été supprimé.');
        return $this->redirectToRoute('app_movie');
    }

    #[Route('/movie/watch', name: 'app_movie_watch')]
    public function watch(
        MovieRepository $movieRepository,
        TmdbService $tmdbService,
    ): Response
    {
        $tmdbService->searchMovie('Dune');
        $user = $this->getUser();
        $movies = $movieRepository->findBy(['user' => $user, 'see' => false]);

        return $this->render('movie/watch.html.twig', [
            'movies' => $movies,
        ]);
    }

    #[Route('/movie/watched', name: 'app_movie_watched')]
    public function watched(
        MovieRepository $movieRepository
    ): Response
    {
        $user = $this->getUser();
        $movies = $movieRepository->findBy(['user' => $user, 'see' => true]);

        return $this->render('movie/watched.html.twig', [
            'movies' => $movies,
        ]);
    }

    #[Route('/movie/search', name: 'app_movie_search')]
    public function search(
        Request $request,
        TmdbService $tmdbService,
        Environment $environment
    ): JsonResponse
    {
        $query = $request->request->get('query');
        $movies = $tmdbService->searchMovie($query);
        return $this->json([
            'html' => $environment->render("movie/_movie_list.html.twig", [
                'movies' => $movies
            ])
        ]);
    }

    #[Route('/movie/save/{id}/{see}', name: 'app_movie_save')]
    public function saveMovie(
        $id,
        $see,
        TmdbService $tmdbService,
        MovieManager $movieManager,
        MovieRepository $movieRepository,
    )
    {
        $user = $this->getUser();
        $movie = $tmdbService->getMovieDetailsWithCredits($id);
        $newMovie = $movieManager->saveMovie($movie, $user, $see);


        return $this->redirectToRoute('app_movie_details', [
            'id' => $newMovie->getId()
        ]);
    }
}