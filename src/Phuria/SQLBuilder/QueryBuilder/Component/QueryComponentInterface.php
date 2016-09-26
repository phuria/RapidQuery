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

/**
 * @author Beniamin Jonatan Šimko <spam@simko.it>
 */
interface QueryComponentInterface
{
    /**
     * @return string
     */
    public function buildSQL();
}