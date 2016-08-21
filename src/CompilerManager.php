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

use Phuria\QueryBuilder\QueryCompiler\QueryCompilerInterface;
use Phuria\QueryBuilder\QueryCompiler\SelectQueryCompiler;
use Phuria\QueryBuilder\QueryCompiler\UpdateQueryCompiler;

/**
 * @author Beniamin Jonatan Šimko <spam@simko.it>
 */
class CompilerManager
{
    /**
     * @var \SplPriorityQueue|QueryCompilerInterface[] $compilers
     */
    private $compilers = [];

    public function __construct()
    {
        $this->compilers = new \SplPriorityQueue();

        $this->addCompiler(new SelectQueryCompiler());
        $this->addCompiler(new UpdateQueryCompiler());
    }

    /**
     * @param QueryCompilerInterface $compiler
     * @param int                    $priority
     *
     * @return $this
     */
    public function addCompiler(QueryCompilerInterface $compiler, $priority = 0)
    {
        $this->compilers->insert($compiler, $priority);

        return $this;
    }

    /**
     * @param QueryBuilder $qb
     *
     * @return string
     */
    public function compile(QueryBuilder $qb)
    {
        foreach ($this->compilers as $compiler) {
            if ($compiler->canHandleQuery($qb)) {
                return $compiler->compile($qb);
            }
        }

        return null;
    }
}