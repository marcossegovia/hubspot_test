<?php

namespace HubspotTest\Partner\Domain\Model\Country;

use HubspotTest\Partner\Domain\Model\Partner\Partner;
use JsonSerializable;

final class Country implements JsonSerializable
{
    private $name;
    private $start_date;
    private $attendees;

    public function __construct(string $a_name, \DateTimeImmutable $a_start_date, array $an_attendees)
    {
        $this->name = $a_name;
        $this->start_date = $a_start_date;
        $this->attendees = $an_attendees;
    }

    public static function instance(string $a_name, \DateTimeImmutable $a_start_date): Country
    {
        return new self($a_name, $a_start_date, []);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function startDate(): \DateTimeImmutable
    {
        return $this->start_date;
    }

    public function attendees(): array
    {
        return $this->attendees;
    }

    public function addAttendee(Partner $partner): void
    {
        $this->attendees[] = $partner->email();
    }

    public function jsonSerialize()
    {
        return [
            'attendeeCount' => \count($this->attendees),
            'attendees' => $this->attendees,
            'name' => $this->name,
            'startDate' => $this->start_date->format('Y-m-d')
        ];
    }
}
