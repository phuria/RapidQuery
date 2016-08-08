<?php

namespace Phuria\QueryBuilder;

use Phuria\QueryBuilder\Expression\ExpressionInterface;
use Phuria\QueryBuilder\Reference\ColumnReference;
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
     * @var array $selectClauses
     */
    private $selectClauses;

    /**
     * @var array $whereClauses
     */
    private $whereClauses;

    /**
     * @var AbstractTable[] $tables
     */
    private $tables = [];

    /**
     * @param TableFactory $tableFactory
     */
    public function __construct(TableFactory $tableFactory = null)
    {
        $this->tableFactory = $tableFactory ?: new TableFactory();
        $this->selectClauses = [];
        $this->whereClauses = [];
    }

    /**
     * @param mixed $clause
     *
     * @return $this
     */
    public function addSelect($clause)
    {
        $this->selectClauses[] = Expr::implode(...func_get_args());

        return $this;
    }

    /**
     * @return $this
     */
    public function andWhere()
    {
        $this->whereClauses[] = Expr::implode(...func_get_args());

        return $this;
    }

    /**
     * @param mixed $table
     *
     * @return AbstractTable
     */
    public function from($table)
    {
        $table = $this->tableFactory->createNewTable($table, $this);



        $this->rootTable = $table;

        return $table;
    }

    public function crossJoin($table)
    {
        $table = $this->tableFactory->createNewTable($table, $this);
        $table->setJoinType(AbstractTable::CROSS_JOIN);

        $this->tables[] = $table;

        return $table;
    }

    public function setAlias($alias)
    {
        $this->rootTable .= ' AS ' . $alias;
    }

    public function buildQuery()
    {
        $selectClauses = array_map(function ($clause) {
            if ($clause instanceof ExpressionInterface) {
                return $clause->compile();
            }

            return $clause;
        }, $this->selectClauses);

        return new Query(implode(', ', $selectClauses), $this->rootTable, $this->whereClauses, $this->tables);
    }
}