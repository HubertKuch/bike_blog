<?php

namespace Hubert\BikeBlog\Configuration;

use Avocado\AvocadoApplication\Attributes\Configuration;
use Avocado\AvocadoApplication\Attributes\ConfigurationProperties;
use Avocado\AvocadoApplication\Attributes\Leaf;
use Avocado\DataSource\DataSource;
use Avocado\DataSource\DataSourceBuilder;
use Avocado\DataSource\Exceptions\CannotBuildDataSourceException;
use Avocado\MysqlDriver\MySQLDriver;

#[Configuration]
#[ConfigurationProperties(prefix: "data-source")]
class DataSourceConfiguration {

    private readonly string $user;
    private readonly string $password;
    private readonly string $server;
    private readonly string $port;
    private readonly string $db;
    private readonly string $charset;

    /**
     * @throws CannotBuildDataSourceException
     */
    #[Leaf]
    public function getDataSource(): DataSource {
        return (new DataSourceBuilder())->username($this->user)
                                        ->password($this->password)
                                        ->server($this->server)
                                        ->port($this->port)
                                        ->driver(MySQLDriver::class)
                                        ->databaseName($this->db)
                                        ->charset($this->charset)
                                        ->build();
    }
}
