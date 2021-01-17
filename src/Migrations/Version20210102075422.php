<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210102075422 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mouvement_line (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, mouvement_id INT DEFAULT NULL, quantity INT DEFAULT NULL, INDEX IDX_6AA1AB0D4584665A (product_id), INDEX IDX_6AA1AB0DECD1C222 (mouvement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mouvement_line ADD CONSTRAINT FK_6AA1AB0D4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE mouvement_line ADD CONSTRAINT FK_6AA1AB0DECD1C222 FOREIGN KEY (mouvement_id) REFERENCES mouvement (id)');
        $this->addSql('ALTER TABLE mouvement DROP FOREIGN KEY FK_5B51FC3E4584665A');
        $this->addSql('DROP INDEX IDX_5B51FC3E4584665A ON mouvement');
        $this->addSql('ALTER TABLE mouvement DROP product_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mouvement_line');
        $this->addSql('ALTER TABLE mouvement ADD product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mouvement ADD CONSTRAINT FK_5B51FC3E4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_5B51FC3E4584665A ON mouvement (product_id)');
    }
}
