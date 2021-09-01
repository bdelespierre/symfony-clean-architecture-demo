<?php

namespace Infrastructure\Service\Api;

use GuzzleHttp\Client as Guzzle;

class EkwaTestClient
{
    private Guzzle $client;

    /**
     * @param string $baseUri
     * @param array<mixed> $options
     */
    public function __construct(
        string $baseUri,
        array $options = ['timeout' => 2.0],
    ) {
        $this->client = new Guzzle(['base_uri' => $baseUri] + $options);
    }

    public function offerList(): mixed
    {
        return $this->getJson('offerList');
    }

    public function promoCodeList(): mixed
    {
        return $this->getJson('promoCodeList');
    }

    private function getJson(string $endpoint): mixed
    {
        $response = $this->client->request('GET', $endpoint);

        if ($response->getStatusCode() != 200) {
            throw new \RuntimeException(
                "Fetching '{$endpoint}' failed with status {$response->getStatusCode()}."
            );
        }

        if (! $response->getBody()->getSize()) {
            throw new \RuntimeException(
                "Fetching '{$endpoint}' failed (empty response)."
            );
        }

        if (
            ! $response->hasHeader('Content-Type')
            || ! str_starts_with($response->getHeader('Content-Type')[0], 'application/json')
        ) {
            throw new \RuntimeException(
                "Fetching '{$endpoint}' failed (response Content-Type is not application/json)."
            );
        }

        $json = json_decode($response->getBody()->getContents());

        if (is_null($json)) {
            throw new \RuntimeException(
                "Fetching '{$endpoint}' failed (cannot decode JSON)."
            );
        }

        return $json;
    }
}
