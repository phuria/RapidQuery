<?php

/**
 * This file is part of Phuria SQL Builder package.
 *
 * Copyright (c) 2016 Beniamin Jonatan Šimko
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phuria\UnderQuery\Tests\Unit\QueryBuilder;

use Phuria\UnderQuery\Tests\TestCase\QueryBuilderTrait;

/**
 * @author Beniamin Jonatan Šimko <spam@simko.it>
 */
class InsertSelectBuilderTest extends \PHPUnit_Framework_TestCase
{
    use QueryBuilderTrait;

    /**
     * @test
     * @covers \Phuria\UnderQuery\QueryBuilder\InsertSelectBuilder
     */
    public function itCanSetSelect()
    {
        $qb = static::phuriaSQL()->createInsertSelect();

        static::assertNull($qb->getSelectInsert());

        $select = static::phuriaSQL()->createSelect();
        $qb->selectInsert($select);

        static::assertSame($select, $qb->getSelectInsert());
    }
}
