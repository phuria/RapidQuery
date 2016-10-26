<?php

/**
 * This file is part of UnderQuery package.
 *
 * Copyright (c) 2016 Beniamin Jonatan Šimko
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phuria\UnderQuery\Tests\Unit\QueryBuilder\Component;

use Phuria\UnderQuery\Tests\TestCase\UnderQueryTrait;

/**
 * @author Beniamin Jonatan Šimko <spam@simko.it>
 */
class TableComponentTraitTest extends \PHPUnit_Framework_TestCase
{
    use UnderQueryTrait;

    /**
     * @test
     * @covers \Phuria\UnderQuery\QueryBuilder\Component\TableComponentTrait
     */
    public function itCanAddRootTable()
    {
        $qb = static::underQuery()->createDelete();

        static::assertEmpty($qb->getRootTables());

        $table = $qb->addRootTable('test');

        static::assertCount(1, $qb->getRootTables());
        static::assertSame('test', $table->getTableName());

        $table = $qb->addRootTable('test', 'a');

        static::assertCount(2, $qb->getRootTables());
        static::assertSame('a', $table->getAlias());
    }
}
