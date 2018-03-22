<?php

namespace HubspotTest\Partner\Infrastructure\ExternalApi\Hubspot\Invitation;

use GuzzleHttp\Client;
use HubspotTest\Partner\Domain\Model\Country\CountryCollection;

final class InvitationRepository
{
    private const BASE_URL = 'https://candidate.hubteam.com/candidateTest/v3/problem/result?userKey=b019afbf09f397b0ed60b9208045';

    private $http_client;

    public function __construct(Client $a_http_client)
    {
        $this->http_client = $a_http_client;
    }

    public function send(CountryCollection $a_country_collection): void
    {
        $response = $this->http_client->post(self::BASE_URL, [
            'body' => \json_encode($a_country_collection)
        ]);
        dump($response);exit();
    }
}
