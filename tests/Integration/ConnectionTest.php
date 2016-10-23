<?php

/**
 * This file is part of Phuria SQL Builder package.
 *
 * Copyright (c) 2016 Beniamin Jonatan Šimko
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phuria\UnderQuery\Tests\Integration;

use Phuria\UnderQuery\Connection\ConnectionInterface;
use Phuria\UnderQuery\Tests\TestCase\DatabaseTestCase;
use Phuria\UnderQuery\Tests\TestCase\QueryBuilderTrait;

/**
 * @author Beniamin Jonatan Šimko <spam@simko.it>
 */
class ConnectionTest extends DatabaseTestCase
{
    use QueryBuilderTrait;

    /**
     * @test
     * @coversNothing
     */
    public function itHasDefaultConnection()
    {
        $qbFactory = static::phuriaSQL();

        $connection = $this->prophesize(ConnectionInterface::class)->reveal();
        $qbFactory->registerConnection($connection);

        $qb = $qbFactory->createSelect();
        $qb->addSelect('*');
        $qb->from('user');
        $query = $qb->buildQuery();

        static::assertSame($connection, $query->getConnection());
    }
}