<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200430142134 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE typology_mi CHANGE description description VARCHAR(1500) DEFAULT NULL');
        $this->addSql('ALTER TABLE systeme_information ADD confidentialite_id INT NOT NULL, CHANGE description description VARCHAR(1500) DEFAULT NULL');
        $this->addSql('ALTER TABLE systeme_information ADD CONSTRAINT FK_59D1097911C0A539 FOREIGN KEY (confidentialite_id) REFERENCES confidentialite (id)');
        $this->addSql('CREATE INDEX IDX_59D1097911C0A539 ON systeme_information (confidentialite_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE systeme_information DROP FOREIGN KEY FK_59D1097911C0A539');
        $this->addSql('DROP INDEX IDX_59D1097911C0A539 ON systeme_information');
        $this->addSql('ALTER TABLE systeme_information DROP confidentialite_id, CHANGE description description VARCHAR(1500) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE typology_mi CHANGE description description VARCHAR(1500) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
