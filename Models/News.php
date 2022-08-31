<?php

namespace Hubert\BikeBlog\Models;

use Carbon\Carbon;
use Avocado\ORM\Attributes\Id;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Avocado\ORM\Attributes\Field;
use Avocado\ORM\Attributes\Table;
use Avocado\Router\AvocadoRequest;

#[Table("news")]
class News {

    #[Id]
    private readonly string $id;
    #[Field]
    private string $title;
    #[Field]
    private string $description;
    #[Field]
    private string $tags;
    #[Field]
    private string $date;

    /** @param Tag[] $tags */
    public function __construct(UuidInterface $id, string $title, string $description, array $tags, Carbon $date) {
        $this->id = $id->toString();
        $this->title = $title;
        $this->description = $description;
        $this->tags = implode(";", array_map(fn($tag) => $tag->getTag(), $tags));
        $this->date = $date->toDateString();
    }

    public static function from(AvocadoRequest $request): News {
        return new News(UuidV4::uuid4(), $request->body['title'], $request->body['description'], array_map(fn($tag) => new Tag($tag), $request->body['tags']), Carbon::createFromFormat("Y-m-d H:i:s", "{$request->body['date']} 00:00:00"));
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

    public function getTags(): array {
        return array_map(fn($tag) => new Tag($tag), explode(";", $this->tags));
    }

    public function getDate(): Carbon {
        return Carbon::createFromFormat("Y-m-d", $this->date);
    }
}
