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

use Phuria\SQLBuilder\QueryBuilder\BuilderInterface;
use Phuria\SQLBuilder\QueryBuilder\Component;
use Phuria\SQLBuilder\QueryBuilder\DeleteBuilder;
use Phuria\SQLBuilder\QueryBuilder\SelectBuilder;
use Phuria\SQLBuilder\Table\AbstractTable;

/**
 * @author Beniamin Jonatan Šimko <spam@simko.it>
 */
class TableCompiler
{
    /**
     * @param AbstractTable $table
     *
     * @return string
     */
    private function compileTableDeclaration(AbstractTable $table)
    {
        $declaration = '';

        if ($table->isJoin()) {
            $declaration .= $table->getJoinType() . ' ';
        }

        $declaration .= $table->getTableName();

        if ($alias = $table->getAlias()) {
            $declaration .= ' AS ' . $alias;
        }

        if ($joinOn = $table->getJoinOn()) {
            $declaration .= ' ON ' . $joinOn;
        }

        return $declaration;
    }

    /**
     * @param BuilderInterface $qb
     *
     * @return string
     */
    public function compileRootTables(BuilderInterface $qb)
    {
        $rootTables = '';

        if ($qb instanceof SelectBuilder || $qb instanceof DeleteBuilder) {
            $rootTables .= 'FROM ';
        }

        if ($qb instanceof Component\TableComponentInterface && $qb->getRootTables()) {
            $rootTables .= implode(', ', array_map([$this, 'compileTableDeclaration'], $qb->getRootTables()));
        } else {
            return '';
        }

        return $rootTables;
    }

    /**
     * @param BuilderInterface $qb
     *
     * @return string
     */
    public function compileJoinTables(BuilderInterface $qb)
    {
        if ($qb instanceof Component\JoinComponentInterface) {
            return implode(' ', array_map([$this, 'compileTableDeclaration'], $qb->getJoinTables()));
        }

        return '';
    }
}