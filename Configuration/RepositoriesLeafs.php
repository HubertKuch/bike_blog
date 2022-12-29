<?php

namespace Hubert\BikeBlog\Configuration;

use Avocado\AvocadoApplication\Attributes\Configuration;
use Avocado\AvocadoApplication\Attributes\Leaf;
use Avocado\ORM\AvocadoRepository;
use Hubert\BikeBlog\Models\Image\Image;
use Hubert\BikeBlog\Models\Meter\Meter;
use Hubert\BikeBlog\Models\News\News;
use Hubert\BikeBlog\Models\Tags\NewsTag;
use Hubert\BikeBlog\Models\Tags\Tag;
use Hubert\BikeBlog\Models\Tags\TagCategory;
use Hubert\BikeBlog\Models\User\User;

#[Configuration]
class RepositoriesLeafs {

    #[Leaf("usersRepository")]
    public function getUsersRepository(): AvocadoRepository {
        return new AvocadoRepository(User::class);
    }

    #[Leaf("metersRepository")]
    public function getMetersRepository(): AvocadoRepository {
        return new AvocadoRepository(Meter::class);
    }

    #[Leaf("newsRepository")]
    public function getNewsRepository(): AvocadoRepository {
        return new AvocadoRepository(News::class);
    }

    #[Leaf("newsTagRepository")]
    public function getNewsTagRepository(): AvocadoRepository {
        return new AvocadoRepository(NewsTag::class);
    }

    #[Leaf("tagsRepository")]
    public function getTagsRepository(): AvocadoRepository {
        return new AvocadoRepository(Tag::class);
    }

    #[Leaf("tagCategoriesRepository")]
    public function getTagsCategoriesRepository(): AvocadoRepository {
        return new AvocadoRepository(TagCategory::class);
    }

    #[Leaf("imagesRepository")]
    public function getImagesRepository(): AvocadoRepository {
        return new AvocadoRepository(Image::class);
    }
}
