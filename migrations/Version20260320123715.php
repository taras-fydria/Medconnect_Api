<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260320123715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE doctor ADD first_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE doctor ADD last_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE doctor ADD specialization VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE doctor ADD license_number VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE doctor DROP first_name');
        $this->addSql('ALTER TABLE doctor DROP last_name');
        $this->addSql('ALTER TABLE doctor DROP specialization');
        $this->addSql('ALTER TABLE doctor DROP license_number');
    }
}
