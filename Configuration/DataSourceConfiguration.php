<?php

namespace Hubert\BikeBlog\Configuration;

use Avocado\DataSource\DataSource;
use Avocado\DataSource\DataSourceBuilder;
use Avocado\DataSource\Database\DatabaseType;
use Avocado\AvocadoApplication\Attributes\Leaf;
use Avocado\AvocadoApplication\Attributes\Configuration;

#[Configuration]
class DataSourceConfiguration {

    #[Leaf]
    public function getDataSource(): DataSource {

        return (new DataSourceBuilder())
            ->username($_ENV['DATABASE_USER'])
            ->password($_ENV['DATABASE_PASSWORD'])
            ->server($_ENV['DATABASE_SERVER'])
            ->port($_ENV['DATABASE_PORT'])
            ->databaseType(DatabaseType::MYSQL)
            ->databaseName($_ENV['DATABASE_NAME'])
            ->charset("utf8")
            ->build();
    }
}
