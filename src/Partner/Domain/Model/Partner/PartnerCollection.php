<?php

namespace HubspotTest\Partner\Domain\Model\Partner;

use HubspotTest\Partner\Domain\Model\Country\Country;
use HubspotTest\Partner\Domain\Model\Country\CountryCollection;

final class PartnerCollection
{
    private $partners;

    public function __construct()
    {
        $this->partners = [];
    }

    public function addPartner(Partner $a_partner): void
    {
        $this->partners[$a_partner->id()] = $a_partner;
    }

    public function removePartner(string $an_id): void
    {
        unset($this->partners[$an_id]);

    }

    public function checkPartners(): CountryCollection
    {
        $countryCollection = new CountryCollection();
        $this->discardPartnersWithoutRowDays();
        $countries = $this->getAllCountries();
        foreach ($countries as $current_country) {
            $dates = [];
            foreach ($this->partners as $current_partner) {
                if ($current_country === $current_partner->country()) {
                    $start_dates_in_row = $current_partner->getStartDateWithTwoDaysRow();
                    if (null === $start_dates_in_row) {
                        continue;
                    }
                    foreach ($start_dates_in_row as $current_date) {
                        if (!isset($dates[$current_date->format('Y-m-d')])) {
                            $dates[$current_date->format('Y-m-d')] = 1;
                        }
                        $dates[$current_date->format('Y-m-d')]++;
                    }
                }
            }
            \arsort($dates);
            \reset($dates);
            $winner_date = \key($dates);
            $country = Country::instance($current_country, new \DateTimeImmutable($winner_date));
            foreach ($this->partners as $current_partner) {
                if ($current_partner->isAvailable($country->startDate())) {
                    $country->addAttendee($current_partner);
                }
            }
            $countryCollection->addCountry($country);
        }

        return $countryCollection;
    }

    private function getAllCountries(): array
    {
        $countries = [];
        foreach ($this->partners as $current_partner) {
            if (!isset($countries[$current_partner->country()])) {
                $countries[$current_partner->country()] = 1;
            } else {
                $countries[$current_partner->country()]++;
            }
        }
        return \array_keys($countries);
    }

    public function partners(): array
    {
        return $this->partners;
    }

    private function discardPartnersWithoutRowDays()
    {
        foreach ($this->partners as $current_partner) {
            $available_days = $current_partner->availableDates();
            $number_of_days = \count($available_days);
            if ($number_of_days < 2) {
                return true;
            }
            $has_to_discard = true;
            for ($i = 0; $i < $number_of_days; $i++) {
                if ($i + 1 >= $number_of_days) {
                    return $has_to_discard;
                }
                $current_day = $available_days[$i];
                $next_day = $available_days[$i + 1];
                $diff = $current_day->diff($next_day);
                if ('1' === $diff->format("%a")) {
                    $has_to_discard = false;
                }
            }

            if ($has_to_discard) {
                $this->removePartner($current_partner->id());
            }
        }
    }
}
