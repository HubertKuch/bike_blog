<?php

namespace Hubert\BikeBlog\Models\Meter;

class NewMeterDto {

    public function __construct(private readonly string $newsId, private readonly float $maxSpeed, private readonly string $description, private readonly float $startState, private readonly float $endState, private readonly float $tripLength, private readonly float $time, private readonly bool $toShow) {
    }

    public function getNewsId(): string {
        return $this->newsId;
    }

    public function getMaxSpeed(): float {
        return $this->maxSpeed;
    }

    public function getDescription(): string {
        return $this->description;
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

    public function getTime(): float {
        return $this->time;
    }

    public function isToShow(): bool {
        return $this->toShow;
    }
}