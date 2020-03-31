<?php

namespace LukeRodham\TrustPilot;

use LukeRodham\TrustPilot\Sections\Reviews;

class TrustPilot
{
    private $client;

    /**
     * TrustPilot constructor.
     *
     * @param string $apiKey
     * @param string $businessUnitId
     * @param string $email
     * @param string $password
     */
    public function __construct($apiKey, $businessUnitId = '', $email = '', $password = '')
    {
        $this->client = new ApiWrapper($apiKey, $businessUnitId, $email, $password);
    }

    /**
     * @return Reviews
     */
    public function reviews()
    {
        return new Reviews($this->client);
    }
}