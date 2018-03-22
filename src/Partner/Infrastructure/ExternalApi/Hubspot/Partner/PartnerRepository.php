<?php

namespace HubspotTest\Partner\Infrastructure\ExternalApi\Hubspot\Partner;

use HubspotTest\Partner\Domain\Infrastructure\HttpClient;
use HubspotTest\Partner\Domain\Model\Core\Http\Request;
use HubspotTest\Partner\Domain\Model\Partner\Partner;
use HubspotTest\Partner\Domain\Model\Partner\PartnerCollection;

final class PartnerRepository
{
    private const BASE_URL = 'https://candidate.hubteam.com/candidateTest/v3/problem/dataset?userKey=b019afbf09f397b0ed60b9208045';

    private $http_client;

    public function __construct(HttpClient $a_http_client)
    {
        $this->http_client = $a_http_client;
    }

    public function findAll(): PartnerCollection
    {
        $request = $this->http_client->createRequest(Request::GET, self::BASE_URL, true);
        $response = $this->http_client->execute($request);

        return $this->hydratePartners($response->body()['partners']);
    }

    private function hydratePartners(array $partners_from_response): PartnerCollection
    {
        $partner_collection = new PartnerCollection();
        foreach ($partners_from_response as $current_partner) {
            $available_dates = [];
            foreach($current_partner['availableDates'] as $current_available_date)
            {
                $available_dates[] = new \DateTimeImmutable($current_available_date);
            }
            $partner = Partner::instance($current_partner['firstName'], $current_partner['lastName'], $current_partner['email'], $current_partner['country'], $available_dates);
            $partner_collection->addPartner($partner);
        }
        return $partner_collection;
    }
}
