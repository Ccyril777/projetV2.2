<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200505094852 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE systeme_information_systeme_information (systeme_information_source INT NOT NULL, systeme_information_target INT NOT NULL, INDEX IDX_32260FD4FAD64A01 (systeme_information_source), INDEX IDX_32260FD4E3331A8E (systeme_information_target), PRIMARY KEY(systeme_information_source, systeme_information_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE systeme_information_systeme_information ADD CONSTRAINT FK_32260FD4FAD64A01 FOREIGN KEY (systeme_information_source) REFERENCES systeme_information (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE systeme_information_systeme_information ADD CONSTRAINT FK_32260FD4E3331A8E FOREIGN KEY (systeme_information_target) REFERENCES systeme_information (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE typology_mi CHANGE description description VARCHAR(1500) DEFAULT NULL');
        $this->addSql('ALTER TABLE systeme_information CHANGE description description VARCHAR(1500) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE systeme_information_systeme_information');
        $this->addSql('ALTER TABLE systeme_information CHANGE description description VARCHAR(1500) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE typology_mi CHANGE description description VARCHAR(1500) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
