<?php

/**
 * This file is part of Phuria SQL Builder package.
 *
 * Copyright (c) 2016 Beniamin Jonatan Šimko
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phuria\SQLBuilder\Query;

use Phuria\SQLBuilder\Connection\ConnectionInterface;
use Phuria\SQLBuilder\Parameter\ParameterManagerInterface;
use Phuria\SQLBuilder\Statement\StatementInterface;

/**
 * @author Beniamin Jonatan Šimko <spam@simko.it>
 */
class Query
{
    /**
     * @var string $sql
     */
    private $sql;

    /**
     * @var ParameterManagerInterface $parameterManager
     */
    private $parameterManager;

    /**
     * @var ConnectionInterface $connection
     */
    private $connection;

    /**
     * @param string                    $sql
     * @param ParameterManagerInterface $parameterManager
     * @param ConnectionInterface       $connection
     */
    public function __construct(
        $sql,
        ParameterManagerInterface $parameterManager,
        ConnectionInterface $connection
    ) {
        $this->sql = $sql;
        $this->parameterManager = $parameterManager;
        $this->connection = $connection;
    }

    /**
     * @return string
     */
    public function getSQL()
    {
        return $this->sql;
    }

    /**
     * @return ConnectionInterface
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return StatementInterface
     */
    public function buildStatement()
    {
        $stmt = $this->connection->prepare($this->sql);
        $this->parameterManager->bindStatement($stmt);

        return $stmt;
    }

    /**
     * @return mixed
     */
    public function fetchScalar()
    {
        $stmt = $this->buildStatement();
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (null === $result) {
            return null;
        }

        return reset($result);
    }

    /**
     * @param int|string $name
     * @param mixed      $value
     *
     * @return $this
     */
    public function setParameter($name, $value)
    {
        $this->parameterManager->createOrGetParameter($name)->setValue($value);

        return $this;
    }
}