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


    public function getDataFrom(string $source): array
    {
        $response = $this->client->request('GET', $source);

        $content = $response->getContent();

        return json_decode($content);
    }


    public function getGameCharacters()
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


    // public function getData(string $entity, int $limit = 0)
    // {
    //     $this->source = $this->baseSource;

    //     $this->setSource([$entity]);

    //     $allData = $this->getDataFrom($this->source);

    //     if ($limit > 0) {
    //         return array_slice($allData, 0, $limit);
    //     }

    //     return $allData;
    // }


    // public function getOneData(string $entity, int $id)
    // {
    //     $this->source = $this->baseSource;

    //     $this->setSource([$entity, $id]);

    //     return $this->getDataFrom($this->source);
    // }


    // public function setSource(array $params)
    // {
    //     foreach ($params as $param) {
    //         $this->source .= "/$param";
    //     }
    // }
}
