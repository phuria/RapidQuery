<?php

/**
 * This file is part of Phuria SQL Builder package.
 *
 * Copyright (c) 2016 Beniamin Jonatan Šimko
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phuria\SQLBuilder\QueryCompiler;

use Phuria\SQLBuilder\QueryBuilder\AbstractBuilder;
use Phuria\SQLBuilder\QueryBuilder\AbstractInsertBuilder;
use Phuria\SQLBuilder\QueryBuilder\BuilderInterface;
use Phuria\SQLBuilder\QueryBuilder\Clause;
use Phuria\SQLBuilder\QueryBuilder\Component;
use Phuria\SQLBuilder\QueryBuilder\DeleteBuilder;
use Phuria\SQLBuilder\QueryBuilder\InsertSelectBuilder;
use Phuria\SQLBuilder\QueryBuilder\UpdateBuilder;

/**
 * @author Beniamin Jonatan Šimko <spam@simko.it>
 */
class QueryCompiler implements QueryCompilerInterface
{
    /**
     * @var TableCompiler
     */
    private $tableCompiler;

    /**
     * @var ReferenceCompiler
     */
    private $referenceCompiler;

    public function __construct()
    {
        $this->tableCompiler = new TableCompiler();
        $this->referenceCompiler = new ReferenceCompiler();
    }

    /**
     * @inheritdoc
     */
    public function compile(BuilderInterface $qb)
    {
        $compiledSQL = $this->compileNativeSQL($qb);

        if ($qb instanceof AbstractBuilder) {
            $references = $qb->getParameterManager()->getReferences();
            $compiledSQL = $this->referenceCompiler->compile($compiledSQL, $references);
        }

        return $compiledSQL;
    }

    /**
     * @param BuilderInterface $qb
     *
     * @return string
     */
    private function compileNativeSQL(BuilderInterface $qb)
    {
        return implode(' ', array_filter([
            $this->compileDelete($qb),
            $this->compileInsert($qb),
            $this->compileUpdate($qb),
            $this->compileSelect($qb),
            $this->tableCompiler->compileRootTables($qb),
            $this->tableCompiler->compileJoinTables($qb),
            $this->compileInsertColumns($qb),
            $this->compileInsertSelect($qb),
            $this->compileSet($qb),
            $this->compileWhere($qb),
            $this->compileInsertValues($qb),
            $this->compileGroupBy($qb),
            $this->compileHaving($qb),
            $this->compileOrderBy($qb),
            $this->compileLimit($qb)
        ]));
    }

    /**
     * @param BuilderInterface $qb
     *
     * @return string
     */
    private function compileDelete(BuilderInterface $qb)
    {
        if ($qb instanceof DeleteBuilder) {
            return $qb->getDeleteClauses() ? 'DELETE ' . implode(', ', $qb->getDeleteClauses()) : 'DELETE';
        }

        return '';
    }

    /**
     * @param BuilderInterface $qb
     *
     * @return string
     */
    private function compileInsert(BuilderInterface $qb)
    {
        if ($qb instanceof AbstractInsertBuilder) {
            return 'INSERT INTO';
        }

        return '';
    }

    /**
     * @param BuilderInterface $qb
     *
     * @return string
     */
    private function compileUpdate(BuilderInterface $qb)
    {
        if ($qb instanceof UpdateBuilder) {
            return $qb->isIgnore() ? 'UPDATE IGNORE' : 'UPDATE';
        }

        return '';
    }

    /**
     * @param BuilderInterface $qb
     *
     * @return string
     */
    private function compileSelect(BuilderInterface $qb)
    {
        if ($qb instanceof Clause\SelectClauseInterface) {
            return 'SELECT ' . implode(', ', $qb->getSelectClauses());
        }

        return '';
    }

    /**
     * @param BuilderInterface $qb
     *
     * @return string
     */
    private function compileSet(BuilderInterface $qb)
    {
        if ($qb instanceof Clause\SetClauseInterface && $qb->getSetClauses()) {
            return 'SET ' . implode(', ', $qb->getSetClauses());
        }

        return '';
    }

    /**
     * @param BuilderInterface $qb
     *
     * @return string
     */
    private function compileWhere(BuilderInterface $qb)
    {
        if ($qb instanceof Clause\WhereClauseInterface && $qb->getWhereClauses()) {
            return 'WHERE ' . implode(' AND ', $qb->getWhereClauses());
        }

        return '';
    }

    /**
     * @param BuilderInterface $qb
     *
     * @return string
     */
    private function compileGroupBy(BuilderInterface $qb)
    {
        if ($qb instanceof Clause\GroupByClauseInterface && $qb->getGroupByClauses()) {
            $clause = 'GROUP BY ' . implode(', ', $qb->getGroupByClauses());

            return $qb->isGroupByWithRollUp() ? $clause . ' WITH ROLLUP' : $clause;
        }

        return '';
    }

    /**
     * @param BuilderInterface $qb
     *
     * @return string
     */
    private function compileHaving(BuilderInterface $qb)
    {
        if ($qb instanceof Clause\HavingClauseInterface && $qb->getHavingClauses()) {
            return 'HAVING ' . implode(' AND ', $qb->getHavingClauses());
        }

        return '';
    }

    /**
     * @param BuilderInterface $qb
     *
     * @return string
     */
    private function compileOrderBy(BuilderInterface $qb)
    {
        if ($qb instanceof Clause\OrderByClauseInterface && $qb->getOrderByClauses()) {
            return 'ORDER BY ' . implode(', ', $qb->getOrderByClauses());
        }

        return '';
    }

    /**
     * @param BuilderInterface $qb
     *
     * @return string
     */
    private function compileLimit(BuilderInterface $qb)
    {
        if ($qb instanceof Clause\LimitClauseInterface && $qb->getLimitClause()) {
            return 'LIMIT ' . $qb->getLimitClause();
        }

        return '';
    }

    /**
     * @param BuilderInterface $qb
     *
     * @return string
     */
    private function compileInsertValues(BuilderInterface $qb)
    {
        if ($qb instanceof Component\InsertValuesComponentInterface) {
            return 'VALUES ' . implode(', ', array_map(function (array $values) {
                return '('. implode(', ', $values) . ')';
            }, $qb->getValues()));
        }

        return '';
    }

    /**
     * @param BuilderInterface $qb
     *
     * @return string
     */
    private function compileInsertSelect(BuilderInterface $qb)
    {
        if ($qb instanceof InsertSelectBuilder) {
            return $qb->getSelectInsert()->buildSQL();
        }

        return '';
    }

    /**
     * @param BuilderInterface $qb
     *
     * @return string
     */
    private function compileInsertColumns(BuilderInterface $qb)
    {
        if ($qb instanceof Clause\InsertColumnsClauseInterface) {
            return '(' . implode(', ', $qb->getColumns()) . ')';
        }

        return '';
    }
}