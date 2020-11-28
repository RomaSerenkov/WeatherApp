<?php
namespace App;

use App\Entity\CityCoordinate;
use App\Entity\Weather;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Response;

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
            $lat = $item->latitude;
            $lon = $item->longitude;
            $cities[] = new CityCoordinate($lat, $lon);
        }

        return $cities;
    }

    /**
     * @param $client
     * @param $cities
     * @param $uri
     * @return array
     */
    public static function getWeather($client, $cities, $uri)
    {
        $weatherCities = [];
        $requests = function ($cities, $uri) {
            foreach ($cities as $city) {
                $lat = $city->getLat();
                $long = $city->getLong();

                $fullUri = $uri . "$lat,$long";

                yield new Request('GET', $fullUri);
            }
        };

        $pool = new Pool($client, $requests($cities, $uri), [
            'fulfilled' => function (Response $response, $index) use($requests, &$weatherCities) {
                $result = json_decode($response->getBody());
                $cityName = $result->location->name;
                $weatherToday = $result->forecast->forecastday[0]->day->condition->text;
                $weatherTomorrow = $result->forecast->forecastday[1]->day->condition->text;

                $weatherCities[$index] = new Weather($cityName, $weatherToday, $weatherTomorrow);
            },
            'rejected' => function (RequestException $reason) {
                echo $reason->getMessage();
            },
        ]);

        $promise = $pool->promise();

        $promise->wait();

        sort($weatherCities);

        return $weatherCities;
    }
}