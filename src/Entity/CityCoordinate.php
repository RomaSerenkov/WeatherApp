<?php
namespace App\Entity;

class CityCoordinate
{
    private float $lat;
    private float $long;

    public function __construct(float $lat, float $long)
    {
        $this->lat = $lat;
        $this->long = $long;
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function getLong(): float
    {
        return $this->long;
    }
}
