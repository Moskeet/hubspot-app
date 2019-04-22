<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190422064525 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE hubspot_token CHANGE vid_offset time_offset VARCHAR(16) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE hubspot_token CHANGE time_offset vid_offset VARCHAR(16) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
