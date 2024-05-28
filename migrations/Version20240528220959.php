<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\ForeignKeyConstraint;

final class Version20240528220959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add availability and vehicle tables with foreign key constraint';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS vehicle (id INT AUTO_INCREMENT NOT NULL, make VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE IF NOT EXISTS availability (id INT AUTO_INCREMENT NOT NULL, vehicle_id INT NOT NULL, depart_date DATE NOT NULL, return_date DATE NOT NULL, price_per_day NUMERIC(10, 2) NOT NULL, status TINYINT(1) NOT NULL, INDEX IDX_3FB7A2BF545317D1 (vehicle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        if (!$this->foreignKeyExists($this->connection, 'availability', 'FK_3FB7A2BF545317D1')) {
            $this->addSql('ALTER TABLE availability ADD CONSTRAINT FK_3FB7A2BF545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE availability DROP FOREIGN KEY IF EXISTS FK_3FB7A2BF545317D1');
        $this->addSql('DROP TABLE IF EXISTS availability');
        $this->addSql('DROP TABLE IF EXISTS vehicle');
    }

    private function foreignKeyExists(Connection $connection, string $tableName, string $foreignKeyName): bool
    {
        $schemaManager = $connection->createSchemaManager();
        $foreignKeys = $schemaManager->listTableForeignKeys($tableName);

        foreach ($foreignKeys as $foreignKey) {
            if ($foreignKey->getName() === $foreignKeyName) {
                return true;
            }
        }

        return false;
    }
}
