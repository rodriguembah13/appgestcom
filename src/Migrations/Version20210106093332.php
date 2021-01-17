<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210106093332 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_line ADD tauxtva DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD entrepot_id INT DEFAULT NULL, ADD date_livraison DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE72831E97 FOREIGN KEY (entrepot_id) REFERENCES entrepot (id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE72831E97 ON orders (entrepot_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_line DROP tauxtva');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE72831E97');
        $this->addSql('DROP INDEX IDX_E52FFDEE72831E97 ON orders');
        $this->addSql('ALTER TABLE orders DROP entrepot_id, DROP date_livraison');
    }
}
