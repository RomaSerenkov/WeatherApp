<?php declare(strict_types=1);

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use \App\Fetch;

final class FetchTest extends TestCase
{
    private Fetch $fetcher;
    private Client $client;
    const URI_API_CITIES  = 'https://api.musement.com/api/v3/cities';
    const URI_API_WEATHER = 'http://api.weatherapi.com/v1/forecast.json?key=766cfd9a90284a46bbf121913202611&days=2&q=';

    public function setUp(): void
    {
        $this->client = new Client();
        $this->fetcher = new Fetch($this->client);
    }

    public function testJsonStructuresСities(): void
    {
        $results = $this->client->request('GET', self::URI_API_CITIES);

        $results = json_decode($results->getBody()->getContents(), true);

        $mandatoryFields = [
            'longitude',
            'latitude'
        ];

        foreach ($results as $result) {
            foreach ($mandatoryFields as $field) {
                $this->assertArrayHasKey($field, $result);
            }
        }
    }

    public function testCities(): void
    {
        $response = $this->fetcher->getCities();

        $this->assertIsArray($response, "Not an array");
        $this->assertNotEmpty($response, "Empty");
    }

    public function testWeathers(): void
    {
        $response = $this->fetcher->getWeather($this->fetcher->getCities());

        $this->assertIsArray($response, "Not an array");
        $this->assertNotEmpty($response, "Empty");
    }
}
