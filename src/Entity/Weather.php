<?php
namespace App\Entity;

class Weather
{
    private string $cityName;
    private string $weatherToday;
    private string $weatherTomorrow;

    public function __construct(string $cityName, string $weatherToday, string $weatherTomorrow)
    {
        $this->cityName = $cityName;
        $this->weatherToday = $weatherToday;
        $this->weatherTomorrow = $weatherTomorrow;
    }

    public function __toString(): string
    {
        return "Processed city {$this->cityName} | {$this->weatherToday} - {$this->weatherTomorrow}";
    }
}
