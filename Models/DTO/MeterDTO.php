<?php

namespace Hubert\BikeBlog\Models\DTO;

use Hubert\BikeBlog\Models\Meter;

class MeterDTO {

    public function __construct(
        public readonly string $id,
        public readonly float  $maxSpeed,
        public readonly float  $time,
        public readonly string $newsId
    ) {
    }

    /**
     * @param Meter[] $meters
     * @return MeterDTO[]
     * */
    public static function fromArray(array $meters): array {
        return array_map(fn($meter) => MeterDTO::from($meter), $meters);
    }

    public static function from(Meter $meter): MeterDTO {
        return new MeterDTO($meter->getId(), $meter->getMaxSpeed(), $meter->getTime(), $meter->getNewsId());
    }
}
