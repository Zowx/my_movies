<?php

namespace App\Service;


use GuzzleHttp\Client;


class TmdbService
{
    private $client;
    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->client = new Client(['base_uri' => 'https://api.themoviedb.org/3/']);
        $this->apiKey = $apiKey;
    }

    public function searchMovie(string $query)
    {
        $response = $this->client->request('GET','search/movie', [
            'query' => [
                'api_key' => $this->apiKey,
                'language' => 'fr-FR',
                'query' => $query,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getMovieDetailsWithCredits($movieId)
    {
        $response = $this->client->request('GET','movie/'.$movieId, [
            'query' => [
                'api_key' => $this->apiKey,
                'language' => 'fr-FR',
                'append_to_response' => 'credits',
            ],
        ]);
        return json_decode($response->getBody(), true);
    }
}
