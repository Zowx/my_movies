<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('getImageUrl', [$this, 'getImageUrl']),
            new TwigFunction('getMovieImageUrl', [$this, 'getMovieImageUrl']),
        ];
    }

    public function getImageUrl($path, $size = 'w500')
    {
        return 'https://image.tmdb.org/t/p/' . $size . $path;
    }

    public function getMovieImageUrl($movie)
    {
        if ($movie->getFile()) {
            return '/uploads/movie/' . $movie->getFile();
        } else {
            return $this->getImageUrl($movie->getPosterPath());
        }
    }
}