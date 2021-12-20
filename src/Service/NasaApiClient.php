<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * A Class for Managing NASA API calls
 * Class NasaApiClient
 * @package App\Service
 */
class NasaApiClient
{
    /**
     * @var HttpClientInterface
     */
    private $client;

    public function __construct(HttpClientInterface $nasaClient)
    {
        $this->client = $nasaClient;
    }

    /**
     * Fetch a picture by date and return the response as an array
     * @param \DateTimeInterface $dateTime
     * @return array
     */
    public function fetchPicture(\DateTimeInterface $dateTime): array
    {
        $response = $this->client->request('GET', '/planetary/apod', [
            'query' => [
                'date' => $dateTime->format('Y-m-d'),
            ],
        ]);

        return $response->toArray();
    }
}