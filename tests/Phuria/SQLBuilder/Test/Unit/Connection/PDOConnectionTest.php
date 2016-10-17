<?php

/**
 * This file is part of Phuria SQL Builder package.
 *
 * Copyright (c) 2016 Beniamin Jonatan Šimko
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phuria\SQLBuilder\Test\Unit\Connection;

use Phuria\SQLBuilder\Connection\PDOConnection;

/**
 * @author Beniamin Jonatan Šimko <spam@simko.it>
 */
class PDOConnectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers \Phuria\SQLBuilder\Connection\PDOConnection
     */
    public function itShouldFetchScalar()
    {
        $pdo = $this->prophesize(\PDO::class);
        $pdoStmt = $this->prophesize(\PDOStatement::class);
        $pdoStmt->execute()->willReturn(null);
        $pdoStmt->rowCount()->willReturn(1);
        $pdoStmt->fetch(\PDO::FETCH_COLUMN)->willReturn('test');
        $pdoStmt = $pdoStmt->reveal();
        $pdo->prepare('test')->willReturn($pdoStmt);
        $pdo = $pdo->reveal();

        $connection = new PDOConnection($pdo);
        static::assertSame('test', $connection->fetchScalar('test'));
    }

    /**
     * @test
     * @covers \Phuria\SQLBuilder\Connection\PDOConnection
     */
    public function itShouldFetchAll()
    {
        $result = [[1,2],[3,4]];

        $pdo = $this->prophesize(\PDO::class);
        $pdoStmt = $this->prophesize(\PDOStatement::class);
        $pdoStmt->execute()->willReturn(null);
        $pdoStmt->rowCount()->willReturn(2);
        $pdoStmt->fetchAll(\PDO::FETCH_ASSOC)->willReturn($result);
        $pdoStmt = $pdoStmt->reveal();
        $pdo->prepare('test')->willReturn($pdoStmt);
        $pdo = $pdo->reveal();

        $connection = new PDOConnection($pdo);
        static::assertSame($result, $connection->fetchAll('test'));
    }
}
