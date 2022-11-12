<?php

namespace Hubert\BikeBlog\Tests\Unit;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class TagsServiceTest extends TestCase {

    private ?Client $client;

    public function testGettingAllTags() {
        $res = $this->client->request("GET", "http://localhost/bike-blog/api/v1/tags");

        $data = $res->getBody()->getContents();
        $data = json_decode($data);

        self::assertIsArray($data);
        $this->assertValidResponse($res);

        $object = $data[0];
        $this->assertIsValidDto($object);
    }

    private function assertValidResponse(ResponseInterface $res) {
        self::assertSame(200, $res->getStatusCode());
        self::assertTrue($res->hasHeader("Content-Type"));
    }

    private function assertIsValidDto(object $object) {
        self::assertObjectHasAttribute("id", $object);
        self::assertObjectHasAttribute("tag", $object);
        self::assertObjectHasAttribute("descriptor", $object);
        self::assertObjectHasAttribute("category", $object);
        self::assertObjectHasAttribute("id", $object->category);
        self::assertObjectHasAttribute("category", $object->category);
    }

    public function testGettingNewsTags() {
        $res = $this->client->request("GET", "http://localhost/bike-blog/api/v1/tags/14c45cdd-32b5-11ed-b21d-4cedfb731ba1");

        $this->assertValidResponse($res);
        $data = $res->getBody()->getContents();
        $data = json_decode($data);

        self::assertIsArray($data);
        $this->assertIsValidDto($data[0]);
    }

    protected function setUp(): void {
        $this->client = new Client(["http_errors" => false]);
    }

    protected function tearDown(): void {
        $this->client = null;
    }
}