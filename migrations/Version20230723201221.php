<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230723201221 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E094B3458B');
        $this->addSql('DROP TABLE adress');
        $this->addSql('DROP INDEX IDX_81398E094B3458B ON customer');
        $this->addSql('ALTER TABLE customer ADD street VARCHAR(255) NOT NULL, ADD postal_code INT NOT NULL, ADD city VARCHAR(50) NOT NULL, ADD country VARCHAR(50) NOT NULL, DROP id_adress_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adress (id INT AUTO_INCREMENT NOT NULL, street VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, postal_code INT NOT NULL, city VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, country VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE customer ADD id_adress_id INT DEFAULT NULL, DROP street, DROP postal_code, DROP city, DROP country');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E094B3458B FOREIGN KEY (id_adress_id) REFERENCES adress (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_81398E094B3458B ON customer (id_adress_id)');
    }
}
