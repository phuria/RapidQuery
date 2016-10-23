<?php

/**
 * This file is part of Phuria SQL Builder package.
 *
 * Copyright (c) 2016 Beniamin Jonatan Šimko
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phuria\UnderQuery\Tests\TestCase;

use Phuria\UnderQuery\Connection\NullConnection;
use Phuria\UnderQuery\PhuriaSQLBuilder;

/**
 * @author Beniamin Jonatan Šimko <spam@simko.it>
 */
trait QueryBuilderTrait
{
    /**
     * @return PhuriaSQLBuilder
     */
    protected static function phuriaSQL()
    {
        $phuriaSQL = new PhuriaSQLBuilder();
        $phuriaSQL->registerConnection(new NullConnection(), 'default');

        return $phuriaSQL;
    }
}