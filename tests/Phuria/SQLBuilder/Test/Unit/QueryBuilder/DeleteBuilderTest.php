<?php

/**
 * This file is part of Phuria SQL Builder package.
 *
 * Copyright (c) 2016 Beniamin Jonatan Šimko
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phuria\SQLBuilder\Test\Unit\QueryBuilder;

use Phuria\SQLBuilder\QueryBuilder;
use Phuria\SQLBuilder\QueryBuilder\DeleteBuilder;

/**
 * @author Beniamin Jonatan Šimko <spam@simko.it>
 */
class DeleteBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return DeleteBuilder
     */
    private function createDeleteBuilder()
    {
        return (new QueryBuilder())->delete();
    }

    /**
     * @test
     */
    public function simpleDelete()
    {
        $qb = $this->createDeleteBuilder();

        $table = $qb->from('example');
        $qb->andWhere("{$table->column('name')} = 'Foo'");

        static::assertSame('DELETE FROM example WHERE example.name = \'Foo\'', $qb->buildSQL());
    }

    /**
     * @test
     */
    public function multipleTableDelete()
    {
        $qb = $this->createDeleteBuilder();

        $qb->from('user', 'u');
        $qb->innerJoin('contact', 'c', 'u.id = c.user_id');
        $qb->addDelete('u');
        $qb->addDelete('c');
        $qb->andWhere('u.id = 1');

        $expectedSQL = 'DELETE u, c FROM user AS u INNER JOIN contact AS c ON u.id = c.user_id WHERE u.id = 1';
        static::assertSame($expectedSQL, $qb->buildSQL());

        $qb = $this->createDeleteBuilder();

        $userTable = $qb->from('user', 'u');
        $contactTable = $qb->innerJoin('contact', 'c', 'u.id = c.user_id');
        $qb->addDelete($userTable, $contactTable);
        $qb->andWhere('u.id = 1');

        static::assertSame($expectedSQL, $qb->buildSQL());
    }
}
