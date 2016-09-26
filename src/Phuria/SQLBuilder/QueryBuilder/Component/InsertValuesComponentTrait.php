<?php

/**
 * This file is part of Phuria SQL Builder package.
 *
 * Copyright (c) 2016 Beniamin Jonatan Šimko
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phuria\SQLBuilder\QueryBuilder\Component;

use Phuria\SQLBuilder\ReferenceManager;

/**
 * @author Beniamin Jonatan Šimko <spam@simko.it>
 */
trait InsertValuesComponentTrait
{
    /**
     * @return ReferenceManager
     */
    abstract public function getReferenceManager();

    /**
     * @var array
     */
    private $values;

    /**
     * @param array $values
     *
     * @return $this
     */
    public function addValues(array $values)
    {
        $valueReferences = [];

        foreach ($values as $value) {
            $valueReferences[] = $this->getReferenceManager()->register($value);
        }

        $this->values[] = $valueReferences;

        return $this;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }
}