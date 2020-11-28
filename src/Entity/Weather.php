<?php


namespace App\Entity;


class Weather
{
    private $cityName;
    private $weatherToday;
    private $weatherTomorrow;

    public function __construct($cityName, $weatherToday, $weatherTomorrow)
    {
        $this->cityName = $cityName;
        $this->weatherToday = $weatherToday;
        $this->weatherTomorrow = $weatherTomorrow;
    }

    public function __toString()
    {
        return "Processed city $this->cityName | $this->weatherToday - $this->weatherTomorrow";
    }
}