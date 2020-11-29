<?php declare(strict_types=1);

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use \App\Fetch;

final class FetchTest extends TestCase
{
    private Fetch $fetcher;

    public function setUp(): void
    {
        $this->fetcher = new Fetch(new Client());
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
