<?php

namespace Hubert\BikeBlog\Services;

use Avocado\ORM\AvocadoRepository;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Attributes\Resource;
use Hubert\BikeBlog\Models\NewsTag;
use Hubert\BikeBlog\Models\Tag;
use Hubert\BikeBlog\Models\TagCategory;

#[Resource(name: "tagsService")]
class TagsService {

    #[Autowired("tagsRepository")]
    private AvocadoRepository $tagsRepo;
    #[Autowired("newsTagRepository")]
    private AvocadoRepository $newsTagRepo;
    #[Autowired("tagCategoriesRepository")]
    private AvocadoRepository $newsCategoryRepo;

    public function __construct() {
    }

    /**
     * @return Tag[]
     * */
    public function getTagsOfNews(string $newsId): array {
        $newsTags = $this->newsTagRepo->findMany(["newsId" => $newsId]);
        $tags = array_map(fn($newsTagData) => $this->tagsRepo->findById($newsTagData->getNewsId()), $newsTags);

        return $tags;
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
}