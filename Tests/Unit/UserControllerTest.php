<?php

namespace Hubert\BikeBlog\Tests\Unit;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class UserControllerTest extends TestCase {

    private static string $userId;
    private ?Client $client;

    public function testRegisteringUser() {
        $payload = ['username' => "__test__", 'password' => '__test__', 'email' => 'email@email.com'];

        $res = $this->client->post("http://localhost/bike-blog/api/v1/users/", ['body' => json_encode($payload)]);


        $userData = json_decode($res->getBody()->getContents());
        var_dump($userData);

        static::$userId = $userData->id;

        self::assertSame(201, $res->getStatusCode());
        self::assertTrue($res->hasHeader("Content-Type"));
        self::assertStringContainsString("application/json", $res->getHeader("Content-Type")[0]);
    }

    public function testDeleteUser() {
        $uuid = static::$userId;
        $res = $this->client->delete("http://localhost/bike-blog/api/v1/users/$uuid/");

        $data = $res->getBody()->getContents();

        self::assertSame(200, $res->getStatusCode());
        self::assertTrue($res->hasHeader("Content-Type"));
        self::assertStringContainsString("application/json", $res->getHeader("Content-Type")[0]);
        self::assertStringContainsString("Deleted", $data);
    }

    protected function setUp(): void {
        $this->client = new Client(["http_errors" => false]);
    }

    protected function tearDown(): void {
        $this->client = null;
    }
}
