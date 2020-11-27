<?php
require 'vendor/autoload.php';

use App\City;
use GuzzleHttp\Client;

$client = new Client();
$result = $client->request('GET','https://api.musement.com/api/v3/cities');

$result = json_decode($result->getBody());

echo count($result);
$city = new City("London", 23,23);
echo $city->getName();
