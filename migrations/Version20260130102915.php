<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260130102915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE blog_meta ADD blog_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog_meta ADD CONSTRAINT FK_372298A5DAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id) NOT DEFERRABLE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE blog_meta DROP CONSTRAINT FK_372298A5DAE07E97');
        $this->addSql('ALTER TABLE blog_meta DROP blog_id');
    }
}
