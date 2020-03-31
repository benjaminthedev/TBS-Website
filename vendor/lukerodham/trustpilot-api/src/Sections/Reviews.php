<?php

namespace LukeRodham\TrustPilot\Sections;

use LukeRodham\TrustPilot\ApiWrapper;
use LukeRodham\TrustPilot\Transformers\ReviewTransformer;

class Reviews
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

    /**
     * @param array $queryParams
     *
     * @return ReviewTransformer[]
     */
    public function latest($queryParams = [])
    {
        $url = '/v1/reviews/latest';

        if ($this->client->getBusinessUnitId() !== '') {
            $url = '/v1/business-units/' . $this->client->getBusinessUnitId() . '/reviews';
        }

        $reviews = $this->client->getClient()->request(
            'GET',
            $url,
            array_merge($this->client->getDefaultHeaders(), ['query' => $queryParams])
        );

        $reviews = json_decode($reviews->getBody()->getContents(), true);

        return (new ReviewTransformer())->transformArray($reviews);
    }

    /**
     * @param array $queryParams
     *
     * @return float
     */
    public function getTrustScore($queryParams = [])
    {
        $data = $this->getBusinessUnit($queryParams);

        return $data['trustScore'];
    }

    /**
     * @param array $queryParams
     *
     * @return mixed
     */
    public function getStarRating($queryParams = [])
    {
        $data = $this->getBusinessUnit($queryParams);

        return $data['stars'];
    }

    /**
     * @param array $queryParams
     *
     * @return mixed
     */
    public function getTotalNumberOfReviews($queryParams = [])
    {
        $data = $this->getBusinessUnit($queryParams);

        return $data['numberOfReviews']['total'];
    }

    /**
     * @param array $queryParams
     *
     * @return mixed
     */
    private function getBusinessUnit($queryParams = [])
    {
        if (isset($this->responses['business_units'])) {
            $response = $this->responses['business_units'];
        } else {
            $url = '/v1/business-units/' . $this->client->getBusinessUnitId();

            $ratings = $this->client->getClient()->request(
                'GET',
                $url,
                array_merge($this->client->getDefaultHeaders(), ['query' => $queryParams])
            );

            $response                          = $ratings->getBody()->getContents();
            $this->responses['business_units'] = $response;
        }

        return json_decode($response, true);
    }
}