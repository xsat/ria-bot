<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220130205343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Realty table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE realty (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, data CLOB NOT NULL --(DC2Type:json))'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE realty');
    }
}
