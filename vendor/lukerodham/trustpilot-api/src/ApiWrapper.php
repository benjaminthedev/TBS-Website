<?php

namespace LukeRodham\TrustPilot;

use GuzzleHttp\Client;
use LukeRodham\TrustPilot\Exceptions\InvalidApiCredentialsException;

class ApiWrapper
{
    /**
     * @var string
     */
    private $apiKey;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $password;
    /**
     * @var Client
     */
    private $client;
    /**
     * @var string
     */
    private $baseUri = 'https://api.trustpilot.com/';
    /**
     * @var string
     */
    private $businessUnitId;

    /**
     * ApiWrapper constructor.
     *
     * @param string $apiKey
     * @param string $businessUnitId
     * @param string $email
     * @param string $password
     */
    public function __construct($apiKey, $businessUnitId = '', $email = '', $password = '')
    {
        $this->apiKey         = $apiKey;
        $this->email          = $email;
        $this->password       = $password;
        $this->businessUnitId = $businessUnitId;

        $this->client = new Client(['base_uri' => $this->baseUri]);
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return array
     * @throws InvalidApiCredentialsException
     */
    public function getDefaultHeaders()
    {
        return [
            'headers' => [
                'apikey' => $this->getApiKey()
            ]
        ];
    }

    /**
     * @return string
     * @throws InvalidApiCredentialsException
     */
    private function getApiKey()
    {
        if (!$this->apiKey) {
            throw new InvalidApiCredentialsException('No API key has been set.');
        }

        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getBusinessUnitId()
    {
        return $this->businessUnitId;
    }
}