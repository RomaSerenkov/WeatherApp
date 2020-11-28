<?php
require 'vendor/autoload.php';

use App\Fetch;
use GuzzleHttp\Client;


const URI_API_CITIES = 'https://api.musement.com/api/v3/cities';
const URI_API_WEATHER = 'http://api.weatherapi.com/v1/forecast.json?key=766cfd9a90284a46bbf121913202611&days=2&q=';

$client = new Client();

$cities = Fetch::getCities($client, URI_API_CITIES);
$weatherCities = Fetch::getWeather($client, $cities, URI_API_WEATHER);

foreach ($weatherCities as $weatherCity)
{
    echo $weatherCity . '<br />';
}


