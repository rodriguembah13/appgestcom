<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210103061018 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE orderfournisseurs ADD entrepot_id INT DEFAULT NULL, ADD date_livraison DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE orderfournisseurs ADD CONSTRAINT FK_96E0CD3972831E97 FOREIGN KEY (entrepot_id) REFERENCES entrepot (id)');
        $this->addSql('CREATE INDEX IDX_96E0CD3972831E97 ON orderfournisseurs (entrepot_id)');
        $this->addSql('ALTER TABLE order_fournisseur_line DROP FOREIGN KEY FK_52A731FA72831E97');
        $this->addSql('DROP INDEX IDX_52A731FA72831E97 ON order_fournisseur_line');
        $this->addSql('ALTER TABLE order_fournisseur_line DROP entrepot_id');
        $this->addSql('ALTER TABLE product ADD supplier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES fournisseur (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD2ADD6D8C ON product (supplier_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_fournisseur_line ADD entrepot_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_fournisseur_line ADD CONSTRAINT FK_52A731FA72831E97 FOREIGN KEY (entrepot_id) REFERENCES entrepot (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_52A731FA72831E97 ON order_fournisseur_line (entrepot_id)');
        $this->addSql('ALTER TABLE orderfournisseurs DROP FOREIGN KEY FK_96E0CD3972831E97');
        $this->addSql('DROP INDEX IDX_96E0CD3972831E97 ON orderfournisseurs');
        $this->addSql('ALTER TABLE orderfournisseurs DROP entrepot_id, DROP date_livraison');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD2ADD6D8C');
        $this->addSql('DROP INDEX IDX_D34A04AD2ADD6D8C ON product');
        $this->addSql('ALTER TABLE product DROP supplier_id');
    }
}
