<?php

namespace HubspotTest\Partner\Infrastructure\Guzzle;

use HubspotTest\Partner\Domain\Infrastructure\HttpClient;
use HubspotTest\Partner\Domain\Model\Core\Http\Request;
use HubspotTest\Partner\Domain\Model\Core\Http\Response;
use Psr\Http\Message\ResponseInterface;

final class Client implements HttpClient
{
    private $client;

    public function __construct(\GuzzleHttp\Client $a_guzzle_client)
    {
        $this->client = $a_guzzle_client;
    }

    public function createRequest(string $a_method, string $a_url, bool $a_verify_ssl_certificate, ?array $some_headers = null, $a_body = null): Request
    {
        return new Request($a_method, $a_url, $a_verify_ssl_certificate, $some_headers, $a_body);
    }

    public function execute(Request $a_request): Response
    {
        $options = $this->buildOptions($a_request);
        $guzzle_response = $this->client->request($a_request->method(), $a_request->url(), $options);
        $response_headers = $this->extractHeaders($guzzle_response);
        $response_body = $this->extractBody($guzzle_response);
        $response_status_code = $guzzle_response->getStatusCode();
        $response_reason = $guzzle_response->getReasonPhrase();

        return new Response($a_request, $response_headers, $response_body, $response_status_code, $response_reason);
    }

    private function buildOptions(Request $a_request): array
    {
        $options = [
            'headers' => $a_request->headers(),
            'http_errors' => false,
            'verify' => $a_request->hasToVerifySsl()
        ];

        if (\is_array($a_request->body())) {
            $options['form_params'] = $a_request->body();
        } else {
            $options['body'] = $a_request->body();
        }

        return $options;
    }

    private function extractBody(ResponseInterface $guzzle_response): ?array
    {
        $string_body = $guzzle_response->getBody()->getContents();
        $body = \json_decode($string_body, true);
        if (null === $body || JSON_ERROR_NONE !== \json_last_error()) {
            if (empty($string_body)) {
                return null;
            }
            return [$string_body];
        }

        return $body;
    }

    private function extractHeaders(ResponseInterface $guzzle_response): array
    {
        $response_headers = [];
        foreach ($guzzle_response->getHeaders() as $header_key => $header_values) {
            $response_headers[$header_key] = \implode(', ', $header_values);
        }

        return $response_headers;
    }
}
