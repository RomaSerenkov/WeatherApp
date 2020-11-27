<?php

namespace App;

class City
{
    private $name;
    private $lat;
    private $lon;

    function __construct($name, $lat, $lon)
    {
        $this->name = $name;
        $this->lat = $lat;
        $this->lon = $lon;
    }

    /**
     * @return mixed
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @return mixed
     */
    public function getLon()
    {
        return $this->lon;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}