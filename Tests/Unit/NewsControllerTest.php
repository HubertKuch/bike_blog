<?php

namespace Hubert\BikeBlog\Tests\Unit;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class NewsControllerTest extends TestCase {

    private ?Client $client;

    public function testGettingNews() {
        $res = $this->client->request("GET", "http://localhost/bike-blog/api/v2/news/");
        $responseData = json_decode($res->getBody()->getContents());

        $this->assertIsValidNewsByYearDTO($res, $responseData);
    }

    private function assertIsValidNewsByYearDTO($response, $responseData): void {
        self::assertObjectHasAttribute("year", $responseData[0]);
        self::assertObjectHasAttribute("news", $responseData[0]);
        self::assertIsArray($responseData[0]->news);
        self::assertEquals(200, $response->getStatusCode());
        self::assertTrue($response->hasHeader("Content-Type"));
    }

    public function testGettingNewsById() {
        $res = $this->client->request("GET", "http://localhost/bike-blog/api/v2/news/xxxx-xxxx-xxxx-xxxx");

        $data = json_decode($res->getBody()->getContents());

        self::assertStringContainsString("not found", $data->message);
        self::assertSame(404, $res->getStatusCode());
        self::assertTrue($res->hasHeader("Content-Type"));
    }

    public function testGettingNewsByTag() {
        $res = $this->client->request("GET", "http://localhost/bike-blog/api/v2/news/tag/unnamed_tag");

        $data = json_decode($res->getBody()->getContents());

        self::assertIsArray($data);
        self::assertSame(200, $res->getStatusCode());
        self::assertTrue($res->hasHeader("Content-Type"));
    }

    public function testGettingTags() {
        $res = $this->client->request("GET", "http://localhost/bike-blog/api/v1/news/tag/tags");

        $data = json_decode($res->getBody()->getContents());

        self::assertIsArray($data);
        self::assertSame(200, $res->getStatusCode());

        self::assertTrue(count(array_filter($data, fn($tag) => is_string($tag))) == count($data));
    }

    protected function setUp(): void {
        $this->client = new Client(["http_errors" => false]);
    }

    protected function tearDown(): void {
        $this->client = null;
    }
}
