<?php
require 'vendor/autoload.php';

use App\Fetch;
use GuzzleHttp\Client;

try {
    $fetcher = new Fetch(new Client());

    $cities   = $fetcher->getCities();
    $weathers = $fetcher->getWeather($cities);

    foreach ($weathers as $weather) {
        echo "{$weather} <br />";
    }

} catch (\Exception $exception) {
    echo $exception->getMessage();
}




