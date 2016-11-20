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

use Phuria\UnderQuery\Parameter\ParameterCollection;
use Phuria\UnderQuery\Parameter\ParameterCollectionInterface;
use Phuria\UnderQuery\Query\Query;
use Phuria\UnderQuery\Reference\ReferenceCollection;
use Phuria\UnderQuery\Reference\ReferenceCollectionInterface;
use Phuria\UnderQuery\Table\AbstractTable;

/**
 * @author Beniamin Jonatan Šimko <spam@simko.it>
 */
abstract class AbstractBuilder implements BuilderInterface
{
    /**
     * @var QueryBuilderFacade
     */
    private $facade;

    /**
     * @var ReferenceCollectionInterface
     */
    private $referenceCollection;

    /**
     * @var ParameterCollectionInterface
     */
    private $parameterCollection;

    /**
     * @var AbstractTable[] $tables
     */
    private $rootTables = [];

    /**
     * @param QueryBuilderFacade $facade
     */
    public function __construct(QueryBuilderFacade $facade)
    {
        $this->facade = $facade;
        $this->parameterCollection = new ParameterCollection();
        $this->referenceCollection = new ReferenceCollection();
    }

    /**
     * @param callable $callback
     *
     * @return BuilderInterface
     */
    public function getQueryBuilder(callable $callback = null)
    {
        $callback && $callback($this);

        return $this;
    }

    /**
     * @return ParameterCollectionInterface
     */
    public function getParameters()
    {
        return $this->parameterCollection;
    }

    /**
     * @return ReferenceCollectionInterface
     */
    public function getReferences()
    {
        return $this->referenceCollection;
    }

    /**
     * @param mixed       $table
     * @param null|string $alias
     *
     * @return AbstractTable
     */
    public function addRootTable($table, $alias = null)
    {
        $this->rootTables[] = $tableObject = $this->createTable($table, $alias);
        is_callable($table) && $table($tableObject);

        return $tableObject;
    }

    /**
     * @return AbstractTable[]
     */
    public function getRootTables()
    {
        return $this->rootTables;
    }

    /**
     * @param mixed       $table
     * @param null|string $alias
     *
     * @return AbstractTable
     */
    public function createTable($table, $alias = null)
    {
        $tableObject = $this->facade->createTable($this, $table);

        if ($alias) {
            $tableObject->setAlias($alias);
        }

        return $tableObject;
    }

    /**
     * @inheritdoc
     */
    public function toReference($stuff)
    {
        return $this->getReferences()->createReference($stuff);
    }

    /**
     * @inheritdoc
     */
    public function setParameter($name, $value)
    {
        $parameter = $this->getParameters()->getParameter($name);
        $parameter->setValue($value);

        return $this;
    }

    /**
     * @return Query
     */
    public function buildQuery()
    {
        return new Query(
            $this->buildSQL(),
            $this->parameterCollection->toArray(),
            $this->facade->getConnection()
        );
    }

    /**
     * @inheritdoc
     */
    public function buildSQL()
    {
        return $this->facade->buildSQL($this);
    }
}