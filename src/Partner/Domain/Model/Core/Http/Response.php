<?php

namespace HubspotTest\Partner\Domain\Model\Core\Http;

final class Response
{
    public const STATUS_CODE = [
        'OK' => 200
    ];

    private $request;
    private $headers;
    private $body;
    private $status_code;
    private $reason;

    public function __construct(Request $a_request, array $some_headers, array $a_body, int $a_status_code, string $a_reason)
    {
        $this->request = $a_request;
        $this->headers = $some_headers;
        $this->body = $a_body;
        $this->status_code = $a_status_code;
        $this->reason = $a_reason;
    }

    public function request(): Request
    {
        return $this->request;
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function body(): array
    {
        return $this->body;
    }

    public function statusCode(): int
    {
        return $this->status_code;
    }

    public function reason(): string
    {
        return $this->reason;
    }
}
