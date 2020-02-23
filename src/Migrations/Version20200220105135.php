<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200220105135 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE pedidos (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, restaurante_id INTEGER NOT NULL, fecha DATE NOT NULL, enviado INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_6716CCAA38B81E49 ON pedidos (restaurante_id)');
        $this->addSql('CREATE TABLE pedidos_producto (pedidos_id INTEGER NOT NULL, producto_id INTEGER NOT NULL, PRIMARY KEY(pedidos_id, producto_id))');
        $this->addSql('CREATE INDEX IDX_C4078F69213530F2 ON pedidos_producto (pedidos_id)');
        $this->addSql('CREATE INDEX IDX_C4078F697645698E ON pedidos_producto (producto_id)');
        $this->addSql('CREATE TABLE categoria (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, descripcion VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE restaurante (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, correo VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5957C27577040BC9 ON restaurante (correo)');
        $this->addSql('CREATE TABLE producto (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, categoria_id INTEGER NOT NULL, nombre VARCHAR(80) NOT NULL, descripcion VARCHAR(255) NOT NULL, peso INTEGER NOT NULL, stock INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_A7BB06153397707A ON producto (categoria_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE pedidos');
        $this->addSql('DROP TABLE pedidos_producto');
        $this->addSql('DROP TABLE categoria');
        $this->addSql('DROP TABLE restaurante');
        $this->addSql('DROP TABLE producto');
    }
}
