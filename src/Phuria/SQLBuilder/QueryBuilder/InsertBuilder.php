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

use Phuria\SQLBuilder\Table\AbstractTable;

/**
 * @author Beniamin Jonatan Šimko <spam@simko.it>
 */
class InsertBuilder extends AbstractBuilder implements
    Clause\InsertColumnsClauseInterface,
    Component\InsertValuesComponentInterface,
    Component\QueryComponentInterface,
    Component\TableComponentInterface
{
    use Clause\InsertColumnsClauseTrait;
    use Component\InsertValuesComponentTrait;
    use Component\QueryComponentTrait;
    use Component\TableComponentTrait;

    /**
     * @param mixed $table
     *
     * @return AbstractTable
     */
    public function insert($table)
    {
        return $this->addRootTable($table);
    }
}