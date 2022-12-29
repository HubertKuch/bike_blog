<?php

namespace Hubert\BikeBlog\Models\News;

use Avocado\ORM\Attributes\Field;
use Avocado\ORM\Attributes\Id;
use Avocado\ORM\Attributes\Table;
use Avocado\Router\AvocadoRequest;
use Carbon\Carbon;
use Hubert\BikeBlog\Models\Tags\Tag;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\UuidInterface;

#[Table("news")]
class News {

    #[Id]
    private readonly string $id;
    #[Field]
    private string $title;
    #[Field]
    private string $description;
    #[Field]
    private string $date;

    /** @param Tag[] $tags */
    public function __construct(UuidInterface $id, string $title, string $description, Carbon $date) {
        $this->id = $id->toString();
        $this->title = $title;
        $this->description = $description;
        $this->date = $date->toDateString();
    }

    public static function from(AvocadoRequest $request): News {
        return new News(UuidV4::uuid4(), $request->body['title'], $request->body['description'], Carbon::createFromFormat("Y-m-d H:i:s", "{$request->body['date']} 00:00:00"));
    }

    public function getId(): string {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function getDate(): Carbon {
        return Carbon::createFromFormat("Y-m-d", $this->date);
    }
}
