<?php

namespace Phuria;

use Phuria\QueryBuilder\QueryBuilder;
use Phuria\QueryBuilder\Table\UnknownTable;
use Phuria\QueryBuilder\TableFactory;
use Phuria\QueryBuilder\TableRegistry;
use Phuria\QueryBuilder\Test\ExampleTable;

/**
 * @author Beniamin Jonatan Šimko <spam@simko.it>
 */
class QueryBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return QueryBuilder
     */
    private function createQb()
    {
        $tableRegistry = new TableRegistry();
        $tableRegistry->registerTable(ExampleTable::class, 'example');
        $tableFactory = new TableFactory($tableRegistry);

        return new QueryBuilder($tableFactory);
    }

    public function testSimpleSelect()
    {
        $qb = new QueryBuilder();

        $rootTable = $qb->from('test');
        $rootTable->addSelect('MIN(test.id)');

        static::assertSame('SELECT MIN(test.id) FROM test', $qb->buildQuery()->getSQL());
    }

    public function testSimpleSelectWithAlias()
    {
        $qb = new QueryBuilder();

        $rootTable = $qb->from('test');
        $rootTable->setAlias('SRC');
        $rootTable->addSelect('MIN(SRC.id)');

        static::assertSame('SELECT MIN(SRC.id) FROM test AS SRC', $qb->buildQuery()->getSQL());
    }

    public function testTwoSelects()
    {
        $qb = new QueryBuilder();

        $rootTable = $qb->from('test');
        $rootTable->addSelect('test.id');
        $rootTable->addSelect('test.name');

        static::assertSame('SELECT test.id, test.name FROM test', $qb->buildQuery()->getSQL());
    }

    public function testCreateUnknownTable()
    {
        $qb = new QueryBuilder();

        $rootTable = $qb->from('unknown_table');

        static::assertInstanceOf(UnknownTable::class, $rootTable);
    }

    public function testCreateExampleTable()
    {
        $qb = $this->createQb();
        $rootTable = $qb->from('example');

        static::assertInstanceOf(ExampleTable::class, $rootTable);
    }

    public function testSelectAndWhereExampleTable()
    {
        $qb = $this->createQb();

        $rootTable = $qb->from('example');
        $rootTable->addSelect('example.id');
        $rootTable->addSelect('example.name');
        $qb->andWhere('example.id BETWEEN 1 AND 10');

        static::assertSame(
            'SELECT example.id, example.name FROM example WHERE example.id BETWEEN 1 AND 10',
            $qb->buildQuery()->getSQL()
        );
    }

    public function testSelectColumnReference()
    {
        $qb = $this->createQb();

        $rootTable = $qb->from('example');
        $rootTable->addSelect($rootTable->column('id'));
        $rootTable->addSelect($rootTable->column('name'));

        static::assertSame('SELECT example.id, example.name FROM example', $qb->buildQuery()->getSQL());

        $rootTable->setAlias('SRC');

        static::assertSame('SELECT SRC.id, SRC.name FROM example AS SRC', $qb->buildQuery()->getSQL());
    }

    public function testSelectColumnReferenceAs()
    {
        $qb = $this->createQb();

        $rootTable = $qb->from('example');
        $qb->addSelect($rootTable->column('id')->alias('primary_identity'));

        static::assertSame('SELECT example.id AS primary_identity FROM example', $qb->buildQuery()->getSQL());
    }

    public function testSelectMax()
    {
        $qb = $this->createQb();

        $rootTable = $qb->from('example');
        $qb->addSelect($rootTable->column('points')->max());

        static::assertSame('SELECT MAX(example.points) FROM example', $qb->buildQuery()->getSQL());

        $rootTable->setAlias('SRC');

        static::assertSame('SELECT MAX(SRC.points) FROM example AS SRC', $qb->buildQuery()->getSQL());
    }

    public function testWhereColumn()
    {
        $qb = $this->createQb();

        $rootTable = $qb->from('example');
        $qb->addSelect($rootTable->column('*'));
        $qb->andWhere($rootTable->column('name'), ' IS NOT NULL');

        static::assertSame('SELECT example.* FROM example WHERE example.name IS NOT NULL', $qb->buildQuery()->getSQL());

        $rootTable->setAlias('SRC');

        static::assertSame('SELECT SRC.* FROM example AS SRC WHERE SRC.name IS NOT NULL', $qb->buildQuery()->getSQL());
    }

    public function testWhereConjunction()
    {
        $qb = $this->createQb();

        $rootTable = $qb->from('example');
        $qb->addSelect('*');
        $qb->andWhere($rootTable->column('name'), ' IS NOT NULL');
        $qb->andWhere($rootTable->column('id'), ' > 10');

        $expectedSQL = 'SELECT * FROM example WHERE example.name IS NOT NULL AND example.id > 10';
        static::assertSame($expectedSQL, $qb->buildQuery()->getSQL());

        $qb->andWhere('(', $rootTable->column('name'), ' = "Albert" OR ', $rootTable->column('name'), ' = "Olaf")');

        $expectedSQL .= ' AND (example.name = "Albert" OR example.name = "Olaf")';
        static::assertSame($expectedSQL, $qb->buildQuery()->getSQL());
    }

    public function testCrossJoin()
    {
        $qb = $this->createQb();

        $qb->from('example');
        $qb->addSelect('*');
        $qb->crossJoin('test');

        static::assertSame('SELECT * FROM example CROSS JOIN test', $qb->buildQuery()->getSQL());
    }

    public function testMultipleFrom()
    {
        $qb = $this->createQb();

        $exampleTable = $qb->from('example');
        $testTable = $qb->addFrom('test');
        $qb->addSelect('*');

        static::assertSame('SELECT * FROM example, test', $qb->buildQuery()->getSQL());

        $exampleTable->setAlias('SRC');
        $testTable->setAlias('OTHER');

        static::assertSame('SELECT * FROM example AS SRC, test AS OTHER', $qb->buildSQL());
    }

    public function testLeftJoin()
    {
        $qb = $this->createQb();

        $exampleTable = $qb->from('example');
        $qb->addSelect('*');
        $testTable = $qb->leftJoin('test');
        $testTable->joinOn($testTable->column('id'), ' = ', $exampleTable->column('test_id'));

        static::assertSame('SELECT * FROM example LEFT JOIN test ON test.id = example.test_id', $qb->buildSQL());
    }
}