<?php

/**
 * This file is part of Phuria SQL Builder package.
 *
 * Copyright (c) 2016 Beniamin Jonatan Šimko
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phuria\SQLBuilder\Connection;

use Phuria\SQLBuilder\Statement\NullStatement;

/**
 * @author Beniamin Jonatan Šimko <spam@simko.it>
 */
class NullConnection implements ConnectionInterface
{
    /**
     * @inheritdoc
     */
    public function query($SQL)
    {
        return new NullStatement();
    }

    /**
     * @inheritdoc
     */
    public function prepare($SQL)
    {
        return new NullStatement();
    }
}