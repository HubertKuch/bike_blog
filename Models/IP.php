<?php

namespace Hubert\BikeBlog\Models;

class IP {

    public function __construct(private readonly string $ip) {
    }

    public static function from(string $ip): IP {
        return new IP($ip);
    }

    public function getIp(): string {
        return $this->ip;
    }
}
