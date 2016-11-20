<?php

/**
 * This file is part of UnderQuery package.
 *
 * Copyright (c) 2016 Beniamin Jonatan Šimko
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phuria\UnderQuery\QueryBuilder\Clause;

/**
 * @author Beniamin Jonatan Šimko <spam@simko.it>
 */
interface OrderByInterface
{
    /**
     * @return array
     */
    public function getOrderByClauses();

    /**
     * @param mixed $_
     *
     * @return $this
     */
    public function addOrderBy($_);
}