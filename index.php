<?php
require 'vendor/autoload.php';

use App\Fetch;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Response;

const URI_API_CITIES = 'https://api.musement.com/api/v3/cities';
const URI_API_WEATHER = 'http://api.weatherapi.com/v1/forecast.json?key=766cfd9a90284a46bbf121913202611&days=2&q=';

$client = new Client();

$cities = Fetch::getCities($client, URI_API_CITIES);

$pool = new Pool($client, Fetch::getWeather($cities, URI_API_WEATHER), [
    'fulfilled' => function (Response $response, $index) {
        $result = json_decode($response->getBody());
        $city = $result->location->name;
        $weatherToday = $result->forecast->forecastday[0]->day->condition->text;
        $weatherTomorrow = $result->forecast->forecastday[1]->day->condition->text;
        echo "Processed city $city | $weatherToday - $weatherTomorrow <br>";
    },
    'rejected' => function (RequestException $reason, $index) {
        echo json_encode($reason->getMessage());
    },
]);

$promise = $pool->promise();

$promise->wait();
