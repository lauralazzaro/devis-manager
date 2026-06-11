<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260611161007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE quote_line (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, quantity DOUBLE PRECISION NOT NULL, unit_price DOUBLE PRECISION NOT NULL, quote_id INT NOT NULL, INDEX IDX_43F3EB7CDB805178 (quote_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE quote_line ADD CONSTRAINT FK_43F3EB7CDB805178 FOREIGN KEY (quote_id) REFERENCES quote (id)');
        $this->addSql('ALTER TABLE quote CHANGE status status VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quote_line DROP FOREIGN KEY FK_43F3EB7CDB805178');
        $this->addSql('DROP TABLE quote_line');
        $this->addSql('ALTER TABLE quote CHANGE status status VARCHAR(50) NOT NULL');
    }
}
