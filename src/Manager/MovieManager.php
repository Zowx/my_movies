<?php

namespace App\Manager;

use App\Entity\Movie;
use App\Entity\User;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class MovieManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private SluggerInterface $slugger,
        private MovieRepository $movieRepository,
    )
    {
    }

    public function new($form, User $user, Movie $movie, $parameter): void
    {
        $data = $form->getData($user);
        $movie->setUser($user);
        $file = $form->get('file')->getData();

        if ($file) {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        }

        $file->move(
            $parameter,
            $newFilename
        );

        $movie->setFile($newFilename);


        $this->entityManager->persist($movie);
        $this->entityManager->flush();
    }

    public function edit($form, Movie $movie, $parameter): void
    {
        $this->entityManager->flush();
    }

    public function changeSee(Movie $movie): void
    {
        $movie->setSee(!$movie->isSee());
        $this->entityManager->flush();
    }

    public function deleteMovie(Movie $movie): void
    {
        $this->entityManager->remove($movie);
        $this->entityManager->flush();
    }
    public function saveMovie(array $data, User $user, string $see)
    {
        $movie = $this->movieRepository->findOneBy(['idMovieApi' => $data['id']]);

        if ($movie) {
            return $movie;
        }

        if ($see === 'true'){
            $see = true;
        }
        else
            $see = false;

        $movie = new Movie();
        $movie->setName($data['title']);
        $movie->setIdMovieApi($data['id']);
        $movie->setDateRelease(\DateTime::createFromFormat('Y-m-d', $data['release_date']));
        $movie->setSynopsis($data['overview']);
        $movie->setUser($user);
        $movie->setPosterPath($data['poster_path']);
        $movie->setSee($see);

        // Récupérer le type de film
        $genre = '';
        foreach ($data['genres'] as $genreData) {
            $genre = $genreData['name'];
            break;
        }
        $movie->setType($genre);

        // Récupérer le nom du réalisateur
        $directorName = '';
        foreach ($data['credits']['crew'] as $crewMember) {
            if ($crewMember['job'] === 'Director') {
                $directorName = $crewMember['name'];
                break;
            }
        }
        $movie->setRealisator($directorName);

        $this->entityManager->persist($movie);
        $this->entityManager->flush();

        return $movie;
    }
}