<?php

namespace Hubert\BikeBlog\Models\Meter;

use Avocado\ORM\Attributes\Field;
use Avocado\ORM\Attributes\Id;
use Avocado\ORM\Attributes\Table;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\UuidInterface;

#[Table("meter")]
class Meter {

    #[Id]
    private string $id;
    #[Field("max_speed")]
    private float $maxSpeed;
    #[Field("meter_start_state")]
    private float $startState;
    #[Field("meter_end_state")]
    private float $endState;
    #[Field("trip_length")]
    private float $tripLength;
    #[Field]
    private float $time;
    #[Field("to_show")]
    private bool $isToShow;
    #[Field("news_id")]
    private string $newsId;

    public function __construct(UuidInterface $id, float $maxSpeed, float $startState, float $endState, float $tripLength, float $time, bool $isToShow, string $newsId) {
        $this->id = $id->toString();
        $this->maxSpeed = $maxSpeed;
        $this->endState = $endState;
        $this->startState = $startState;
        $this->tripLength = $tripLength;
        $this->time = $time;
        $this->isToShow = $isToShow;
        $this->newsId = $newsId;
    }

    public static function from(NewMeterDto $newMeterDto): Meter {
        return new Meter(UuidV4::uuid4(), $newMeterDto->getMaxSpeed(), $newMeterDto->getTime(), $newMeterDto->getStartState(), $newMeterDto->getEndState(), $newMeterDto->getTripLength(), $newMeterDto->isToShow(), $newMeterDto->getNewsId());
    }

    public function getMaxSpeed(): float {
        return $this->maxSpeed;
    }

    public function getTime(): float {
        return $this->time;
    }

    public function getStartState(): float {
        return $this->startState;
    }

    public function getEndState(): float {
        return $this->endState;
    }

    public function getTripLength(): float {
        return $this->tripLength;
    }

    public function isToShow(): bool {
        return $this->isToShow;
    }

    public function getNewsId(): string {
        return $this->newsId;
    }

    public function getId(): string {
        return $this->id;
    }
}
