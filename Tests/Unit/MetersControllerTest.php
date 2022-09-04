<?php

namespace Hubert\BikeBlog\Tests\Unit;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class MetersControllerTest extends TestCase {
    private ?Client $client;

    public function testGettingMetersByNewsId() {
        $res = $this->client->request("GET", "http://localhost/bike-blog/api/v1/meters/xxxx-xxxx-xxxx-xxxx");

        self::assertSame(404, $res->getStatusCode());
        self::assertTrue($res->hasHeader("Content-Type"));
        self::assertStringContainsString("application/json", $res->getHeader("Content-Type")[0]);
        self::assertStringContainsString("not found", $res->getBody()->getContents());
    }

    protected function setUp(): void {
        $this->client = new Client(["http_errors" => false]);
    }

    protected function tearDown(): void {
        $this->client = null;
    }
}
