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
    #[Field]
    private float $time;
    #[Field("to_show")]
    private bool $isToShow;
    #[Field("news_id")]
    private string $newsId;

    public function __construct(UuidInterface $id, float $maxSpeed, float $time, bool $isToShow, string $newsId) {
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
}
