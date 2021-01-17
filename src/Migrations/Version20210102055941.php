<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210102055941 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_fournisseur_line ADD entrepot_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_fournisseur_line ADD CONSTRAINT FK_52A731FA72831E97 FOREIGN KEY (entrepot_id) REFERENCES entrepot (id)');
        $this->addSql('CREATE INDEX IDX_52A731FA72831E97 ON order_fournisseur_line (entrepot_id)');
        $this->addSql('ALTER TABLE entrepot ADD adresse VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE entrepot DROP adresse');
        $this->addSql('ALTER TABLE order_fournisseur_line DROP FOREIGN KEY FK_52A731FA72831E97');
        $this->addSql('DROP INDEX IDX_52A731FA72831E97 ON order_fournisseur_line');
        $this->addSql('ALTER TABLE order_fournisseur_line DROP entrepot_id');
    }
}
