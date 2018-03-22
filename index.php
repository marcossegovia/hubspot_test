<?php

require __DIR__ . '/vendor/autoload.php';


/**
 *  DEPENDENCY INJECTION
 */
$guzzle_client = new \GuzzleHttp\Client();
$http_client = new \HubspotTest\Partner\Infrastructure\Guzzle\Client($guzzle_client);
$partner_repository = new \HubspotTest\Partner\Infrastructure\ExternalApi\Hubspot\Partner\PartnerRepository($http_client);
$invitation_repository = new \HubspotTest\Partner\Infrastructure\ExternalApi\Hubspot\Invitation\InvitationRepository($guzzle_client);

$partner_collection = $partner_repository->findAll();
$country_collection = $partner_collection->checkPartners();
$invitation_repository->send($country_collection);
