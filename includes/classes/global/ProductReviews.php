<?php
/**
 * Created by PhpStorm.
 * User: connormulhall
 * Date: 28/06/2017
 * Time: 10:12
 */

namespace LukeRodham\TrustPilot\Sections;


use LukeRodham\TrustPilot\ApiWrapper;

class ProductReviews
{

    /**
     * @var ApiWrapper
     */
    private $client;
    /**
     * @var array
     */
    private $responses = [];

    /**
     * @param ApiWrapper $client
     */
    public function __construct(ApiWrapper $client)
    {
        $this->client = $client;
    }

    public function getProductReviews($queryParams = [])
    {

        $url = '/v1/product-reviews/reviews';

        if (!key_exists('sku', $queryParams))
            return 'SKU is missing';


        if ($this->client->getBusinessUnitId() !== '') {
            $url = '/v1/product-reviews/business-units/' . $this->client->getBusinessUnitId() . '/reviews';
        }

        $reviews = $this->client->getClient()->request(
            'GET',
            $url,
            array_merge($this->client->getDefaultHeaders(), ['query' => $queryParams])
        );

        return $reviews->getBody()->getContents();
    }
}