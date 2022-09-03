<?php

namespace Hubert\BikeBlog\Models\DTO;

use Hubert\BikeBlog\Models\Meter;

class MeterDTO {

    public function __construct(public string $id, public float $maxSpeed, public float $time, public string $newsId) {
    }

    public static function from(Meter $meter): MeterDTO {
        return new MeterDTO($meter->getId(), $meter->getMaxSpeed(), $meter->getTime(), $meter->getNewsId());
    }

    public function getId(): string {
        return $this->id;
    }

    public function getMaxSpeed(): float {
        return $this->maxSpeed;
    }

    public function getTime(): float {
        return $this->time;
    }

    public function getNewsId(): string {
        return $this->newsId;
    }
}
