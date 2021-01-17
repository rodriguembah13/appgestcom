<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210105204311 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mouvement_line DROP tauxremise, DROP tauxtva, DROP prix_ttc, DROP total_tva');
        $this->addSql('ALTER TABLE order_fournisseur_line ADD tauxremise DOUBLE PRECISION DEFAULT NULL, ADD tauxtva DOUBLE PRECISION DEFAULT NULL, ADD total_tva DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mouvement_line ADD tauxremise DOUBLE PRECISION DEFAULT NULL, ADD tauxtva DOUBLE PRECISION DEFAULT NULL, ADD prix_ttc DOUBLE PRECISION DEFAULT NULL, ADD total_tva DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE order_fournisseur_line DROP tauxremise, DROP tauxtva, DROP total_tva');
    }
}
