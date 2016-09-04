<?php

/**
 * This file is part of Phuria SQL Builder package.
 *
 * Copyright (c) 2016 Beniamin Jonatan Šimko
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phuria\QueryBuilder;

use Phuria\QueryBuilder\Connection\ConnectionInterface;
use Phuria\QueryBuilder\Parameter\ParameterManager;
use Phuria\QueryBuilder\Parameter\ParameterManagerInterface;
use Phuria\QueryBuilder\QueryCompiler\QueryCompiler;
use Phuria\QueryBuilder\QueryCompiler\QueryCompilerInterface;
use Phuria\QueryBuilder\Table\AbstractTable;

/**
 * @author Beniamin Jonatan Šimko <spam@simko.it>
 */
class QueryBuilder
{
    /**
     * @var TableFactory $tableFactory
     */
    private $tableFactory;

    /**
     * @var QueryCompilerInterface $queryCompiler
     */
    private $queryCompiler;

    /**
     * @var QueryClauses
     */
    private $queryClauses;

    /**
     * @var AbstractTable[] $tables
     */
    private $tables = [];

    /**
     * @var ReferenceManager $referenceManager
     */
    private $referenceManager;

    /**
     * @var ParameterManagerInterface $parameterManager
     */
    private $parameterManager;

    /**
     * @param TableFactory           $tableFactory
     * @param QueryCompilerInterface $queryCompiler
     */
    public function __construct(
        TableFactory $tableFactory = null,
        QueryCompilerInterface $queryCompiler = null
    ) {
        $this->tableFactory = $tableFactory ?: new TableFactory();
        $this->queryCompiler = $queryCompiler ?: new QueryCompiler();
        $this->queryClauses = new QueryClauses();
        $this->referenceManager = new ReferenceManager();
        $this->parameterManager = new ParameterManager();
    }

    /**
     * @param string $clause
     *
     * @return $this
     */
    public function addSelect($clause)
    {
        $this->queryClauses->addSelect($clause);

        return $this;
    }

    /**
     * @param string $clause
     *
     * @return $this
     */
    public function andWhere($clause)
    {
        $this->queryClauses->andWhere($clause);

        return $this;
    }

    /**
     * @param string $clause
     *
     * @return $this
     */
    public function andHaving($clause)
    {
        $this->queryClauses->andHaving($clause);

        return $this;
    }

    /**
     * @param string $clause
     *
     * @return $this
     */
    public function addOrderBy($clause)
    {
        $this->queryClauses->addOrderBy($clause);

        return $this;
    }

    /**
     * @param string $clause
     *
     * @return $this
     */
    public function addSet($clause)
    {
        $this->queryClauses->addSet($clause);

        return $this;
    }

    /**
     * @param string $clause
     *
     * @return $this
     */
    public function addGroupBy($clause)
    {
        $this->queryClauses->addGroupBy($clause);

        return $this;
    }

    /**
     * @return QueryClauses
     */
    public function getQueryClauses()
    {
        return $this->queryClauses;
    }

    /**
     * @param $table
     *
     * @return AbstractTable
     */
    private function addRootTable($table)
    {
        $table = $this->tableFactory->createNewTable($table, $this);
        $table->setRoot(true);

        $this->tables[] = $table;

        return $table;
    }

    /**
     * @param mixed $table
     *
     * @return AbstractTable
     */
    public function from($table)
    {
        return $this->addFrom($table);
    }

    /**
     * @param mixed $table
     *
     * @return AbstractTable
     */
    public function addFrom($table)
    {
        return $this->addRootTable($table);
    }

    /**
     * @param mixed $table
     *
     * @return AbstractTable
     */
    public function update($table)
    {
        return $this->addRootTable($table);
    }

    /**
     * @param string $joinType
     * @param mixed  $table
     *
     * @return AbstractTable
     */
    public function join($joinType, $table)
    {
        $table = $this->tableFactory->createNewTable($table, $this);
        $table->setJoinType($joinType);

        $this->tables[] = $table;

        return $table;
    }

    /**
     * @param mixed $table
     *
     * @return AbstractTable
     */
    public function crossJoin($table)
    {
        return $this->join(AbstractTable::CROSS_JOIN, $table);
    }

    /**
     * @param mixed $table
     *
     * @return AbstractTable
     */
    public function leftJoin($table)
    {
        return $this->join(AbstractTable::LEFT_JOIN, $table);
    }

    /**
     * @param string $table
     *
     * @return AbstractTable
     */
    public function innerJoin($table)
    {
        return $this->join(AbstractTable::INNER_JOIN, $table);
    }

    /**
     * @param string $clause
     *
     * @return $this
     */
    public function limit($clause)
    {
        $this->queryClauses->setLimit($clause);

        return $this;
    }

    /**
     * @param int   $hint
     * @param mixed $value
     *
     * @return $this
     */
    public function addQueryHint($hint, $value = null)
    {
        $this->queryClauses->addQueryHint($hint, $value);

        return $this;
    }

    /**
     * @return string
     */
    public function buildSQL()
    {
        return $this->queryCompiler->compile($this);
    }

    /**
     * @param ConnectionInterface $connection
     *
     * @return Query
     */
    public function buildQuery(ConnectionInterface $connection = null)
    {
        return new Query(
            $this->buildSQL(),
            $this->parameterManager,
            $connection
        );
    }

    /**
     * @return AbstractTable[]
     */
    public function getTables()
    {
        return $this->tables;
    }

    /**
     * @return AbstractTable[]
     */
    public function getRootTables()
    {
        return array_filter($this->getTables(), function(AbstractTable $table) {
            return $table->isRoot();
        });
    }

    /**
     * @return AbstractTable[]
     */
    public function getJoinTables()
    {
        return array_filter($this->getTables(), function(AbstractTable $table) {
            return $table->isJoin();
        });
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

    /**
     * @return ReferenceManager
     */
    public function getReferenceManager()
    {
        return $this->referenceManager;
    }
}