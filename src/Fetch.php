<?php declare(strict_types=1);
namespace App;

use App\Entity\CityCoordinate;
use App\Entity\Weather;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Response;

class Fetch
{
    const URI_API_CITIES  = 'https://api.musement.com/api/v3/cities';
    const URI_API_WEATHER = 'http://api.weatherapi.com/v1/forecast.json?key=766cfd9a90284a46bbf121913202611&days=2&q=';

    const HTTP_OK = 200;

    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return array<CityCoordinate>
     * @throws Exception
     */
    public function getCities(): array
    {
        $cities = [];

        $res = $this->client->request('GET', self::URI_API_CITIES);

        if (self::HTTP_OK !== $res->getStatusCode()) {
            throw new Exception("Resource has returned {$res->getStatusCode()} code.");
        }

        $items = json_decode($res->getBody()->getContents());

        foreach ($items as $item) {
            $lat      = $item->latitude;
            $lon      = $item->longitude;

            $cities[] = new CityCoordinate($lat, $lon);
        }

        return $cities;
    }

    /**
     * @param array<CityCoordinate> $cities
     * @return array<Weather>
     */
    public function getWeather(array $cities): array
    {
        $weathers = [];
        $requests = function ($cities, $uri) {
            foreach ($cities as $city) {
                $lat  = $city->getLat();
                $long = $city->getLong();

                $fullUri = "{$uri}{$lat},{$long}";

                yield new Request('GET', $fullUri);
            }
        };

        $pool = new Pool(
            $this->client,
            $requests($cities, self::URI_API_WEATHER),
            [
                'fulfilled' => function (Response $response, $index) use (&$weathers) {
                    $result          = json_decode($response->getBody()->getContents());
                    $cityName        = $result->location->name;
                    $weatherToday    = $result->forecast->forecastday[0]->day->condition->text;
                    $weatherTomorrow = $result->forecast->forecastday[1]->day->condition->text;

                    $weathers[$index] = new Weather($cityName, $weatherToday, $weatherTomorrow);
                },

                'rejected'  => function (RequestException $reason) {
                    throw new Exception($reason->getMessage());
                },
            ]
        );

        $promise = $pool->promise();
        $promise->wait();

        sort($weathers);

        return $weathers;
    }
}
