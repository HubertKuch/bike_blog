<?php

namespace Hubert\BikeBlog\Models;

use Avocado\ORM\Attributes\Id;
use Ramsey\Uuid\UuidInterface;
use Avocado\ORM\Attributes\Table;
use Avocado\ORM\Attributes\Field;

#[Table("meter")]
class Meter {

    #[Id]
    private string $id;
    #[Field("max_speed")]
    private float $maxSpeed;
    #[Field]
    private float $time;
    #[Field]
    private bool $isToShow;
    #[Field]
    private string $newsId;

    public function __construct(UuidInterface $id, float $maxSpeed, float $time, bool $isToShow, string $newsId) {
        $this->id = $id->toString();
        $this->maxSpeed = $maxSpeed;
        $this->time = $time;
        $this->isToShow = $isToShow;
        $this->newsId = $newsId;
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

    public function isToShow(): bool {
        return $this->isToShow;
    }

    public function getNewsId(): string {
        return $this->newsId;
    }
}
