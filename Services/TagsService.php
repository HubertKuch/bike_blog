<?php

namespace Hubert\BikeBlog\Services;

use Avocado\ORM\AvocadoRepository;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Attributes\Resource;
use Hubert\BikeBlog\Models\DTO\OutgoingTagDto;
use Hubert\BikeBlog\Models\Tags\CategoriesWIthTagsCredentials;
use Hubert\BikeBlog\Models\Tags\NewsTag;
use Hubert\BikeBlog\Models\Tags\Tag;
use Hubert\BikeBlog\Models\Tags\TagCategory;

#[Resource(name: "tagsService")]
class TagsService {

    #[Autowired("tagsRepository")]
    private AvocadoRepository $tagsRepo;
    #[Autowired("newsTagRepository")]
    private AvocadoRepository $newsTagRepo;
    #[Autowired("tagCategoriesRepository")]
    private AvocadoRepository $newsCategoryRepo;

    public function __construct() {}

    public function getAllTagsAsDto(): array {
        /** @var TagCategory[] $categories */
        $categories = $this->newsCategoryRepo->findMany();
        $data = [];

        foreach ($categories as $category) {
            $relatedTags = $this->tagsRepo->findMany(["category_id" => $category->getId()]);

            $data[] = new CategoriesWIthTagsCredentials($category, $this->parseArrayToDto($relatedTags));
        }

        return $data;
    }

    public function parseArrayToDto(array $tags): array {
        return array_map(fn($tag) => $this->parseSingleToDto($tag), $tags);
    }

    public function parseSingleToDto(Tag $tag): OutgoingTagDto {
        $category = $this->newsCategoryRepo->findById($tag->getCategoryId());

        return OutgoingTagDto::from($tag, $category);
    }

    /**
     * @return Tag[]
     * */
    public function getTagsOfNews(string $newsId): array {
        $newsTags = $this->newsTagRepo->findMany(["news_id" => $newsId]);

        return array_map(fn($newsTagData) => $this->tagsRepo->findById($newsTagData->getTagId()), $newsTags);
    }

    public function getTagsByName(string $tagName): ?Tag {
        return $this->tagsRepo->findFirst(["tag" => "$tagName"]);
    }

    /**
     * @return NewsTag[]
     * */
    public function getNewsTagByTagId(string $tagId): array {
        return $this->newsTagRepo->findMany(["tag_id" => $tagId]);
    }

    public function getTagCategory(string $tagId): TagCategory {
        $tag = $this->tagsRepo->findById($tagId);
        $category = $this->newsCategoryRepo->findById($tag->getCategoryId());

        return $category;
    }

    public function addTagToNews(Tag $tag, string $newsId): void {
        $this->tagsRepo->save($tag);
        $newsTag = new NewsTag($newsId, $tag->getId());

        $this->newsTagRepo->save($newsTag);
    }

    public function getTagById(string $id): Tag {
        return $this->tagsRepo->findById($id);
    }
}