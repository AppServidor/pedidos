<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200220115641 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('ALTER TABLE restaurante ADD COLUMN nombre VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX IDX_6716CCAA38B81E49');
        $this->addSql('CREATE TEMPORARY TABLE __temp__pedidos AS SELECT id, restaurante_id, fecha, enviado FROM pedidos');
        $this->addSql('DROP TABLE pedidos');
        $this->addSql('CREATE TABLE pedidos (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, restaurante_id INTEGER NOT NULL, fecha DATE NOT NULL, enviado INTEGER NOT NULL, CONSTRAINT FK_6716CCAA38B81E49 FOREIGN KEY (restaurante_id) REFERENCES restaurante (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO pedidos (id, restaurante_id, fecha, enviado) SELECT id, restaurante_id, fecha, enviado FROM __temp__pedidos');
        $this->addSql('DROP TABLE __temp__pedidos');
        $this->addSql('CREATE INDEX IDX_6716CCAA38B81E49 ON pedidos (restaurante_id)');
        $this->addSql('DROP INDEX IDX_C4078F697645698E');
        $this->addSql('DROP INDEX IDX_C4078F69213530F2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__pedidos_producto AS SELECT pedidos_id, producto_id FROM pedidos_producto');
        $this->addSql('DROP TABLE pedidos_producto');
        $this->addSql('CREATE TABLE pedidos_producto (pedidos_id INTEGER NOT NULL, producto_id INTEGER NOT NULL, PRIMARY KEY(pedidos_id, producto_id), CONSTRAINT FK_C4078F69213530F2 FOREIGN KEY (pedidos_id) REFERENCES pedidos (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C4078F697645698E FOREIGN KEY (producto_id) REFERENCES producto (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO pedidos_producto (pedidos_id, producto_id) SELECT pedidos_id, producto_id FROM __temp__pedidos_producto');
        $this->addSql('DROP TABLE __temp__pedidos_producto');
        $this->addSql('CREATE INDEX IDX_C4078F697645698E ON pedidos_producto (producto_id)');
        $this->addSql('CREATE INDEX IDX_C4078F69213530F2 ON pedidos_producto (pedidos_id)');
        $this->addSql('DROP INDEX IDX_A7BB06153397707A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__producto AS SELECT id, categoria_id, nombre, descripcion, peso, stock FROM producto');
        $this->addSql('DROP TABLE producto');
        $this->addSql('CREATE TABLE producto (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, categoria_id INTEGER NOT NULL, nombre VARCHAR(80) NOT NULL COLLATE BINARY, descripcion VARCHAR(255) NOT NULL COLLATE BINARY, peso INTEGER NOT NULL, stock INTEGER NOT NULL, CONSTRAINT FK_A7BB06153397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO producto (id, categoria_id, nombre, descripcion, peso, stock) SELECT id, categoria_id, nombre, descripcion, peso, stock FROM __temp__producto');
        $this->addSql('DROP TABLE __temp__producto');
        $this->addSql('CREATE INDEX IDX_A7BB06153397707A ON producto (categoria_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_6716CCAA38B81E49');
        $this->addSql('CREATE TEMPORARY TABLE __temp__pedidos AS SELECT id, restaurante_id, fecha, enviado FROM pedidos');
        $this->addSql('DROP TABLE pedidos');
        $this->addSql('CREATE TABLE pedidos (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, restaurante_id INTEGER NOT NULL, fecha DATE NOT NULL, enviado INTEGER NOT NULL)');
        $this->addSql('INSERT INTO pedidos (id, restaurante_id, fecha, enviado) SELECT id, restaurante_id, fecha, enviado FROM __temp__pedidos');
        $this->addSql('DROP TABLE __temp__pedidos');
        $this->addSql('CREATE INDEX IDX_6716CCAA38B81E49 ON pedidos (restaurante_id)');
        $this->addSql('DROP INDEX IDX_C4078F69213530F2');
        $this->addSql('DROP INDEX IDX_C4078F697645698E');
        $this->addSql('CREATE TEMPORARY TABLE __temp__pedidos_producto AS SELECT pedidos_id, producto_id FROM pedidos_producto');
        $this->addSql('DROP TABLE pedidos_producto');
        $this->addSql('CREATE TABLE pedidos_producto (pedidos_id INTEGER NOT NULL, producto_id INTEGER NOT NULL, PRIMARY KEY(pedidos_id, producto_id))');
        $this->addSql('INSERT INTO pedidos_producto (pedidos_id, producto_id) SELECT pedidos_id, producto_id FROM __temp__pedidos_producto');
        $this->addSql('DROP TABLE __temp__pedidos_producto');
        $this->addSql('CREATE INDEX IDX_C4078F69213530F2 ON pedidos_producto (pedidos_id)');
        $this->addSql('CREATE INDEX IDX_C4078F697645698E ON pedidos_producto (producto_id)');
        $this->addSql('DROP INDEX IDX_A7BB06153397707A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__producto AS SELECT id, categoria_id, nombre, descripcion, peso, stock FROM producto');
        $this->addSql('DROP TABLE producto');
        $this->addSql('CREATE TABLE producto (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, categoria_id INTEGER NOT NULL, nombre VARCHAR(80) NOT NULL, descripcion VARCHAR(255) NOT NULL, peso INTEGER NOT NULL, stock INTEGER NOT NULL)');
        $this->addSql('INSERT INTO producto (id, categoria_id, nombre, descripcion, peso, stock) SELECT id, categoria_id, nombre, descripcion, peso, stock FROM __temp__producto');
        $this->addSql('DROP TABLE __temp__producto');
        $this->addSql('CREATE INDEX IDX_A7BB06153397707A ON producto (categoria_id)');
        $this->addSql('DROP INDEX UNIQ_5957C27577040BC9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__restaurante AS SELECT id, correo, roles, password FROM restaurante');
        $this->addSql('DROP TABLE restaurante');
        $this->addSql('CREATE TABLE restaurante (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, correo VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO restaurante (id, correo, roles, password) SELECT id, correo, roles, password FROM __temp__restaurante');
        $this->addSql('DROP TABLE __temp__restaurante');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5957C27577040BC9 ON restaurante (correo)');
    }
}
