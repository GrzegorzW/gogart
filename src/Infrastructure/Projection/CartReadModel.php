<?php

declare(strict_types = 1);

namespace Gogart\Infrastructure\Projection;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Prooph\EventStore\Projection\AbstractReadModel;

class CartReadModel extends AbstractReadModel
{
    public const TABLE = 'read_cart';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws DBALException
     */
    public function init(): void
    {
        $tableName = self::TABLE;

        $sql = <<<EOT
CREATE TABLE `$tableName` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cart_id` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `product_id` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_cart_id` (`cart_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
EOT;

        $statement = $this->connection->prepare($sql);
        $statement->execute();
    }

    /**
     * @return bool
     *
     * @throws DBALException
     */
    public function isInitialized(): bool
    {
        $tableName = self::TABLE;

        $sql = "SHOW TABLES LIKE '$tableName';";

        $statement = $this->connection->prepare($sql);
        $statement->execute();

        $result = $statement->fetch();

        return (bool)$result;
    }

    /**
     * @throws DBALException
     */
    public function reset(): void
    {
        $tableName = self::TABLE;

        $sql = "TRUNCATE TABLE '$tableName';";

        $statement = $this->connection->prepare($sql);
        $statement->execute();
    }

    /**
     * @throws DBALException
     */
    public function delete(): void
    {
        $tableName = self::TABLE;

        $sql = "DROP TABLE $tableName;";

        $statement = $this->connection->prepare($sql);
        $statement->execute();
    }

    /**
     * @param array $data
     */
    protected function insert(array $data): void
    {
        $this->connection->insert(self::TABLE, $data);
    }

    /**
     * @param array $data
     *
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    protected function deleteProduct(array $data): void
    {
        $this->connection->delete(self::TABLE, $data);
    }
}