<?php

namespace HubspotTest\Partner\Domain\Model\Partner;

use DateTime;

final class Partner
{
    private $id;
    private $first_name;
    private $last_name;
    private $email;
    private $country;
    private $available_dates;

    private function __construct(string $id, string $a_first_name, string $a_last_name, string $an_email, string $a_country, array $some_available_dates)
    {
        $this->id = $id;
        $this->first_name = $a_first_name;
        $this->last_name = $a_last_name;
        $this->email = $an_email;
        $this->country = $a_country;
        $this->available_dates = $some_available_dates;
    }

    public static function instance(string $a_first_name, string $a_last_name, string $an_email, string $a_country, array $some_available_dates): Partner
    {
        $id = strtolower($a_first_name) . '-' . strtolower($a_last_name);

        return new self($id, $a_first_name, $a_last_name, $an_email, $a_country, $some_available_dates);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function firstName(): string
    {
        return $this->first_name;
    }

    public function lastName(): string
    {
        return $this->last_name;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function country(): string
    {
        return $this->country;
    }

    public function availableDates(): array
    {
        return $this->available_dates;
    }

    public function getStartDateWithTwoDaysRow(): ?array
    {
        $number_of_days = \count($this->available_dates);
        $start_dates = [];

        if ($number_of_days === 1) return null;
        for ($i = 0; $i < $number_of_days; $i++) {
            if ($i + 1 >= $number_of_days) {
                return $start_dates;
            }
            $current_day = $this->available_dates[$i];
            $next_day = $this->available_dates[$i + 1];
            $diff = $current_day->diff($next_day);
            if ('1' === $diff->format("%a")) {
                $start_dates[] = $current_day;
            }
        }
        return $start_dates;
    }

    public function isAvailable(\DateTimeImmutable $a_datetime): bool
    {
        foreach ($this->available_dates as $current_date) {
            if ($current_date->format('Y-m-d') === $a_datetime->format('Y-m-d')) {
                $next = (new DateTime($current_date->format('Y-m-d')))->modify('+1 day');
                if ($this->containsDate($next))
                {
                    return true;
                }
            }
        }
        return false;
    }

    private function containsDate(\DateTime $a_datetime): bool
    {
        foreach ($this->available_dates as $current_date) {
            if ($current_date->format('Y-m-d') === $a_datetime->format('Y-m-d')) {
                return true;
            }
        }
        return false;
    }
}
