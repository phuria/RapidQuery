<?php

/**
 * This file is part of UnderQuery package.
 *
 * Copyright (c) 2016 Beniamin Jonatan Šimko
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phuria\UnderQuery\QueryBuilder;

use Phuria\UnderQuery\Connection\ConnectionManagerInterface;
use Phuria\UnderQuery\QueryCompiler\QueryCompilerInterface;
use Phuria\UnderQuery\Statement\StatementInterface;
use Phuria\UnderQuery\Table\AbstractTable;
use Phuria\UnderQuery\TableFactory\TableFactoryInterface;

/**
 * @author Beniamin Jonatan Šimko <spam@simko.it>
 */
class QueryBuilderFacade
{
    /**
     * @var TableFactoryInterface
     */
    private $tableFactory;

    /**
     * @var QueryCompilerInterface
     */
    private $queryCompiler;

    /**
     * @var ConnectionManagerInterface
     */
    private $connectionManager;

    /**
     * @param TableFactoryInterface      $tableFactory
     * @param QueryCompilerInterface     $queryCompiler
     * @param ConnectionManagerInterface $connection
     */
    public function __construct(
        TableFactoryInterface      $tableFactory,
        QueryCompilerInterface     $queryCompiler,
        ConnectionManagerInterface $connection
    ) {
        $this->tableFactory = $tableFactory;
        $this->queryCompiler = $queryCompiler;
        $this->connectionManager = $connection;
    }

    /**
     * @param mixed $builder
     *
     * @return string
     */
    public function buildSQL($builder)
    {
        return $this->queryCompiler->compile($builder);
    }

    /**
     * @param string $compiledSQL
     * @param array  $parameters
     * @param mixed  $connectionHint
     *
     * @return StatementInterface
     */
    public function buildStatement($compiledSQL, array $parameters, $connectionHint)
    {
        return $this->connectionManager->prepareStatement($compiledSQL, $parameters, $connectionHint);
    }

    /**
     * @param mixed $builder
     * @param mixed $table
     *
     * @return AbstractTable
     */
    public function createTable($builder, $table)
    {
        return $this->tableFactory->createNewTable($table, $builder);
    }
}