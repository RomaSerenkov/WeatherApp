<?php
namespace App;

use GuzzleHttp\Psr7\Request;

class Fetch
{
    /**
     * @param $client
     * @param $uri
     * @return array
     */
    public static function getCities($client, $uri)
    {
        $cities = [];

        $res = $client->request('GET', $uri);
        $items = json_decode($res->getBody());

        foreach ($items as $item)
        {
            $name = $item->name;
            $lat = $item->latitude;
            $lon = $item->longitude;
            $cities[] = new City($name, $lat, $lon);
        }

        return $cities;
    }

    /**
     * @param $cities
     * @param $uri
     * @return \Generator
     */
    public static function getWeather($cities, $uri)
    {
        foreach ($cities as $city)
        {
            $lat = $city->getLat();
            $lon = $city->getLon();
            $fullUri = $uri . "$lat,$lon";
            yield new Request('GET', $fullUri);
        }
    }
}