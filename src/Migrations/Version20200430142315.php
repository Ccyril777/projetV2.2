<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200430142315 extends AbstractMigration
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
        $this->addSql('ALTER TABLE systeme_information ADD domaine_id INT NOT NULL, CHANGE description description VARCHAR(1500) DEFAULT NULL');
        $this->addSql('ALTER TABLE systeme_information ADD CONSTRAINT FK_59D109794272FC9F FOREIGN KEY (domaine_id) REFERENCES domaine (id)');
        $this->addSql('CREATE INDEX IDX_59D109794272FC9F ON systeme_information (domaine_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE systeme_information DROP FOREIGN KEY FK_59D109794272FC9F');
        $this->addSql('DROP INDEX IDX_59D109794272FC9F ON systeme_information');
        $this->addSql('ALTER TABLE systeme_information DROP domaine_id, CHANGE description description VARCHAR(1500) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE typology_mi CHANGE description description VARCHAR(1500) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
