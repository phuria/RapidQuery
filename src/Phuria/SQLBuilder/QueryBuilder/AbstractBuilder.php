<?php

/**
 * This file is part of Phuria SQL Builder package.
 *
 * Copyright (c) 2016 Beniamin Jonatan Šimko
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phuria\SQLBuilder\QueryBuilder;

use Phuria\SQLBuilder\Parameter\ParameterCollectionInterface;
use Phuria\SQLBuilder\Query\Query;
use Phuria\SQLBuilder\Query\QueryFactoryInterface;
use Phuria\SQLBuilder\QueryCompiler\QueryCompilerInterface;
use Phuria\SQLBuilder\Reference\ReferenceCollectionInterface;
use Phuria\SQLBuilder\TableFactory\TableFactoryInterface;

/**
 * @author Beniamin Jonatan Šimko <spam@simko.it>
 */
abstract class AbstractBuilder implements BuilderInterface
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
     * @var ParameterCollectionInterface
     */
    private $parameterCollection;

    /**
     * @var ReferenceCollectionInterface
     */
    private $referenceCollection;

    /**
     * @var QueryFactoryInterface
     */
    private $queryFactory;

    /**
     * @param TableFactoryInterface     $tableFactory
     * @param QueryCompilerInterface    $queryCompiler
     * @param QueryFactoryInterface     $queryFactory
     * @param array                     $options
     */
    public function __construct(
        TableFactoryInterface $tableFactory,
        QueryCompilerInterface $queryCompiler,
        QueryFactoryInterface $queryFactory,
        array $options
    ) {
        $this->tableFactory = $tableFactory;
        $this->queryCompiler = $queryCompiler;
        $this->queryFactory = $queryFactory;

        $this->parameterCollection = new $options['parameter_collection_class'];
        $this->referenceCollection = new $options['reference_collection_class'];
    }

    /**
     * @return BuilderInterface
     */
    public function getQueryBuilder()
    {
        return $this;
    }

    /**
     * @return TableFactoryInterface
     */
    public function getTableFactory()
    {
        return $this->tableFactory;
    }

    /**
     * @return QueryCompilerInterface
     */
    public function getQueryCompiler()
    {
        return $this->queryCompiler;
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
     * @inheritdoc
     */
    public function buildSQL()
    {
        return $this->getQueryCompiler()->compile($this->getQueryBuilder());
    }

    /**
     * @inheritdoc
     */
    public function objectToString($object)
    {
        return $this->getReferences()->createReference($object);
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
     * @param mixed $connectionHint
     *
     * @return Query
     */
    public function buildQuery($connectionHint = 'default')
    {
        return $this->queryFactory->buildQuery(
            $this->buildSQL(),
            $this->getParameters()->toArray(),
            $connectionHint
        );
    }
}