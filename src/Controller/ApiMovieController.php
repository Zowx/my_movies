<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ApiMovieController extends AbstractController
{
    #[Route('/apiMovie', name: 'app_api_movie')]
    public function getMovieList(MovieRepository $movieRepository, Request $request): JsonResponse
    {
        $apiKey = $request->headers->get('api_key');
        if ($apiKey !== $_ENV['API_KEY']) {
            return $this->json(['error' => 'Invalid API key'], 401);
        }

        $movies = $movieRepository->findAll();

        $movieArray = [];
        foreach ($movies as $movie) {
            $movieArray[] = [
                'id' => $movie->getId(),
                'name' => $movie->getName(),
                'genres' => $movie->getType(),
                'watch' => $movie->isSee(),
                'director' => $movie->getRealisator(),
                'dateRelease' => $movie->getDateRelease()->format('d/m/y'),
                'synopsis' => $movie->getSynopsis(),
            ];
        }

        return $this->json($movieArray);
    }

    #[Route('/apiMovie/genre/{genre}', name: 'app_api_movie_genre')]
    public function getMoviesByGenre(string $genre, MovieRepository $movieRepository, Request $request): JsonResponse
    {
        $apiKey = $request->headers->get('api_key');
        if ($apiKey !== $_ENV['API_KEY']) {
            return $this->json(['error' => 'Invalid API key'], 401);
        }

        $movies = $movieRepository->findBy(['type' => $genre]);

        $movieArray = [];
        foreach ($movies as $movie) {
            $movieArray[] = [
                'id' => $movie->getId(),
                'name' => $movie->getName(),
                'genres' => $movie->getType(),
                'watch' => $movie->isSee(),
                'director' => $movie->getRealisator(),
                'dateRelease' => $movie->getDateRelease()->format('d/m/y'),
                'synopsis' => $movie->getSynopsis(),
            ];
        }

        return $this->json($movieArray);
    }

    #[Route('/apiMovie/watched', name: 'app_api_movie_watched')]
    public function getWatchedMovies(MovieRepository $movieRepository, Request $request): JsonResponse
    {
        $apiKey = $request->headers->get('api_key');
        if ($apiKey !== $_ENV['API_KEY']) {
            return $this->json(['error' => 'Invalid API key'], 401);
        }

        $movies = $movieRepository->findBy(['see' => true]);

        $movieArray = [];
        foreach ($movies as $movie) {
            $movieArray[] = [
                'id' => $movie->getId(),
                'name' => $movie->getName(),
                'genres' => $movie->getType(),
                'watch' => $movie->isSee(),
                'director' => $movie->getRealisator(),
                'dateRelease' => $movie->getDateRelease()->format('d/m/y'),
                'synopsis' => $movie->getSynopsis(),
            ];
        }

        return $this->json($movieArray);
    }

    #[Route('/apiMovie/unwatched', name: 'app_api_movie_unwatched')]
    public function getUnwatchedMovies(MovieRepository $movieRepository, Request $request): JsonResponse
    {
        $apiKey = $request->headers->get('api_key');
        if ($apiKey !== $_ENV['API_KEY']) {
            return $this->json(['error' => 'Invalid API key'], 401);
        }

        $movies = $movieRepository->findBy(['see' => false]);

        $movieArray = [];
        foreach ($movies as $movie) {
            $movieArray[] = [
                'id' => $movie->getId(),
                'name' => $movie->getName(),
                'genres' => $movie->getType(),
                'watch' => $movie->isSee(),
                'director' => $movie->getRealisator(),
                'dateRelease' => $movie->getDateRelease()->format('d/m/y'),
                'synopsis' => $movie->getSynopsis(),
            ];
        }

        return $this->json($movieArray);
    }

    #[Route('/apiMovie/user/{userId}', name: 'app_api_movie_user')]
    public function getMoviesByUser(int $userId, MovieRepository $movieRepository, Request $request): JsonResponse
    {
        $apiKey = $request->headers->get('api_key');
        if ($apiKey !== $_ENV['API_KEY']) {
            return $this->json(['error' => 'Invalid API key'], 401);
        }

        $movies = $movieRepository->findBy(['user' => $userId]);

        $movieArray = [];
        foreach ($movies as $movie) {
            $movieArray[] = [
                'id' => $movie->getId(),
                'name' => $movie->getName(),
                'genres' => $movie->getType(),
                'watch' => $movie->isSee(),
                'director' => $movie->getRealisator(),
                'dateRelease' => $movie->getDateRelease()->format('d/m/y'),
                'synopsis' => $movie->getSynopsis(),
            ];
        }

        return $this->json($movieArray);
    }
}
