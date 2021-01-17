<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201230122624 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE orderfournisseurs (id INT AUTO_INCREMENT NOT NULL, fournisseur_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, payment_method VARCHAR(255) DEFAULT NULL, currency VARCHAR(255) DEFAULT NULL, order_key VARCHAR(255) DEFAULT NULL, total NUMERIC(10, 0) DEFAULT NULL, refund_total NUMERIC(10, 0) DEFAULT NULL, shipping_total NUMERIC(10, 0) DEFAULT NULL, discount_total NUMERIC(10, 0) DEFAULT NULL, total_tax NUMERIC(10, 0) DEFAULT NULL, amount NUMERIC(10, 0) DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, date_paid DATETIME DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, INDEX IDX_96E0CD39670C757F (fournisseur_id), INDEX IDX_96E0CD39B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_fournisseur_line (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, order_fournisseur_id INT DEFAULT NULL, quantity INT DEFAULT NULL, total NUMERIC(10, 0) DEFAULT NULL, total_tax NUMERIC(10, 0) DEFAULT NULL, INDEX IDX_52A731FA4584665A (product_id), INDEX IDX_52A731FAF7CA5CF (order_fournisseur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE orderfournisseurs ADD CONSTRAINT FK_96E0CD39670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id)');
        $this->addSql('ALTER TABLE orderfournisseurs ADD CONSTRAINT FK_96E0CD39B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE order_fournisseur_line ADD CONSTRAINT FK_52A731FA4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE order_fournisseur_line ADD CONSTRAINT FK_52A731FAF7CA5CF FOREIGN KEY (order_fournisseur_id) REFERENCES orderfournisseurs (id)');
        $this->addSql('ALTER TABLE entrepot DROP dateborn, DROP lieunaissance, DROP sexe, DROP adresse, DROP image_filename');
        $this->addSql('ALTER TABLE orders ADD amount NUMERIC(10, 0) DEFAULT NULL');
        $this->addSql('ALTER TABLE order_line ADD total_tax NUMERIC(10, 0) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_fournisseur_line DROP FOREIGN KEY FK_52A731FAF7CA5CF');
        $this->addSql('DROP TABLE orderfournisseurs');
        $this->addSql('DROP TABLE order_fournisseur_line');
        $this->addSql('ALTER TABLE entrepot ADD dateborn DATE DEFAULT NULL, ADD lieunaissance VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD sexe VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD adresse VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD image_filename VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE order_line DROP total_tax');
        $this->addSql('ALTER TABLE orders DROP amount');
    }
}
