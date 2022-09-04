<?php

namespace Hubert\BikeBlog\Configuration;

use Hubert\BikeBlog\Models\User;
use Hubert\BikeBlog\Models\News;
use Hubert\BikeBlog\Models\Meter;
use Avocado\ORM\AvocadoRepository;
use Avocado\AvocadoApplication\Attributes\Leaf;
use Avocado\AvocadoApplication\Attributes\Configuration;

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
}
