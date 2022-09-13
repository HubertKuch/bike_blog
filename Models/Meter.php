<?php

namespace Hubert\BikeBlog\Models;

use Avocado\ORM\Attributes\Id;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Avocado\ORM\Attributes\Table;
use Avocado\ORM\Attributes\Field;
use Avocado\Router\AvocadoRequest;

#[Table("meter")]
class Meter {

    #[Id]
    private string $id;
    #[Field("max_speed")]
    private float $maxSpeed;
    #[Field("meter_end_state")]
    private float $startState;
    #[Field("meter_end_state")]
    private float $endState;
    #[Field]
    private float $time;
    #[Field("to_show")]
    private bool $isToShow;
    #[Field("news_id")]
    private string $newsId;

    public function __construct(
        UuidInterface $id,
        float         $maxSpeed,
        float         $startState,
        float         $endState,
        float         $time,
        bool          $isToShow,
        string        $newsId) {
        $this->id = $id->toString();
        $this->maxSpeed = $maxSpeed;
        $this->time = $time;
        $this->isToShow = $isToShow;
        $this->newsId = $newsId;
    }

    public static function fromRequest(AvocadoRequest $request): Meter {
        return new Meter(
            UuidV4::uuid4(),
            $request->body['maxSpeed'],
            $request->body['time'],
            $request->body['startState'],
            $request->body['endState'],
            $request->body['toShow'],
            $request->body['newsId'],);
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

    public function getStartState(): float {
        return $this->startState;
    }

    public function getEndState(): float {
        return $this->endState;
    }
}
