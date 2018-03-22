<?php

namespace HubspotTest\Partner\Domain\Model\Core\Http;

final class Request
{
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    private $method;
    private $url;
    private $verify_ssl_certificate;
    private $headers;
    private $body;

    public function __construct(string $a_method, string $a_url, bool $a_verify_ssl_certificate, ?array $some_headers, $a_body)
    {
        $this->method = $a_method;
        $this->url = $a_url;
        $this->verify_ssl_certificate = $a_verify_ssl_certificate;
        $this->headers = $some_headers;
        $this->body = $a_body;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function headers(): ?array
    {
        return $this->headers;
    }

    public function body()
    {
        return $this->body;
    }

    public function hasToVerifySsl(): bool
    {
        return $this->verify_ssl_certificate;
    }
}
