<?php
/**
 * Created by PhpStorm.
 * User: connormulhall
 * Date: 28/06/2017
 * Time: 10:26
 */

namespace LukeRodham\TrustPilot;


use LukeRodham\TrustPilot\Sections\ProductReviews;

class TrustPilotAdditions extends TrustPilot
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
     * @return ProductReviews
     */
    public function productReviews()
    {
        return new ProductReviews($this->client);
    }

}