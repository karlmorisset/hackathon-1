<?php

namespace App\Model;

use Symfony\Component\HttpClient\HttpClient;

class ApiManager
{
    private $client;
    private string $baseSource;

    public function __construct()
    {
        $this->client = HttpClient::create();
        $this->baseSource = "https://www.breakingbadapi.com/api";
    }


    /**
     * Récupère des données depuis une source
     *
     * @param string $source
     * @return array
     */
    public function getDataFrom(string $source): array
    {
        $response = $this->client->request('GET', $source);

        $content = $response->getContent();

        return json_decode($content);
    }


    /**
     * Génère aléatoirement une réponse et des suggestions de réponses
     *
     * @return array
     */
    public function getGameCharacters(): array
    {
        $allCharacters = $this->getDataFrom("{$this->baseSource}/characters");

        $keys = array_rand($allCharacters, 4);

        $answer = $allCharacters[$keys[array_rand($keys)]];
        $suggestions = [];

        for ($i = 0; $i < 4; $i++) {
            $suggestions[$keys[$i]] = $allCharacters[$keys[$i]];
        }

        return [
            "answer" => $answer,
            "suggestions" => $suggestions
        ];
    }
}
