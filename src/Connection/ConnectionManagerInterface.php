<?php

/**
 * This file is part of UnderQuery package.
 *
 * Copyright (c) 2016 Beniamin Jonatan Šimko
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phuria\UnderQuery\Connection;

use Phuria\UnderQuery\Exception\ConnectionException;
use Phuria\UnderQuery\Statement\StatementInterface;

/**
 * @author Beniamin Jonatan Šimko <spam@simko.it>
 */
interface ConnectionManagerInterface
{
    /**
     * @param ConnectionInterface $connection
     * @param string              $name
     *
     * @return void
     */
    public function registerConnection(ConnectionInterface $connection, $name);

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasConnection($name);

    /**
     * @param string|null $name
     *
     * @return ConnectionInterface
     * @throws ConnectionException
     */
    public function getConnection($name);

    /**
     * @param string $compiledSQL
     * @param array  $parameters
     * @param mixed  $connectionHint
     *
     * @return StatementInterface
     */
    public function prepareStatement($compiledSQL, array $parameters, $connectionHint);
}