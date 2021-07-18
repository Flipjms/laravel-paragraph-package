<?php

namespace Pushkin;

use GuzzleHttp\Client as Guzzle;

class Client {
    protected $client;

    protected $projectId;

    const PAGE_TYPE_WEB = 0;
    const PAGE_TYPE_EMAIL = 1;

    public function __construct()
    {
        $this->projectId = config('services.pushkin.project_id');

        $this->client = new Guzzle([
            'base_uri' => config('services.pushkin.api_url'),
            'headers' => [
                'Authorization' => 'Bearer ' . config('services.pushkin.api_key')
            ]
        ]);
    }

    /**
     * @param string $locale
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function downloadTranslations($locale = null)
    {
        $response = $this->client->get("{$this->projectId}/translations", [
            'query' => array_filter([
                'locale' => $locale
            ])
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * @param $texts
     * @return bool
     */
    public function submitTexts($texts)
    {
        $response = $this->client->post("{$this->projectId}/texts", [
            'json' => $texts
        ]);

        return true;
    }

    /**
     * @param $snapshot
     * @param $context
     * @param $type
     * @param $name
     * @param null $sequence
     * @return bool
     */
    public function submitPage($snapshot, $context, $type = Client::PAGE_TYPE_WEB, $name = 'Untitled', $sequence = null)
    {
        $response = $this->client->post("{$this->projectId}/pages", [
            'json' => compact('snapshot', 'context', 'type', 'name', 'sequence')
        ]);

        return true;
    }
}
