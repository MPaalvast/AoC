<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260425122748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $aocYear = $schema->createTable('aoc_year');
        $aocYear->addColumn('id', 'integer', ['autoincrement' => true]);
        $aocYear->addColumn('year_number', 'integer');
        $aocYear->addColumn('max_days', 'integer');
        $aocYear->setPrimaryKey(['id']);
        $aocYear->addUniqueIndex(['year_number'], 'uniq_aoc_year_year_number');

        $aocDay = $schema->createTable('aoc_day');
        $aocDay->addColumn('id', 'integer', ['autoincrement' => true]);
        $aocDay->addColumn('day_number', 'integer');
        $aocDay->addColumn('part1_status', 'string', ['length' => 20]);
        $aocDay->addColumn('part2_status', 'string', ['length' => 20]);
        $aocDay->addColumn('year_id', 'integer');
        $aocDay->setPrimaryKey(['id']);
        $aocDay->addIndex(['year_id'], 'IDX_1E12C74B40C1FEA7');
        $aocDay->addUniqueIndex(['year_id', 'day_number'], 'uniq_aoc_day');
        $aocDay->addForeignKeyConstraint('aoc_year', ['year_id'], ['id'], ['onDelete' => 'CASCADE'], 'FK_1E12C74B40C1FEA7');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('aoc_day');
        $schema->dropTable('aoc_year');
    }
}
