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

/**
 * @author Beniamin Jonatan Šimko <spam@simko.it>
 * @codeCoverageIgnore
 */
class NullConnection implements ConnectionInterface
{
    /**
     * @inheritdoc
     */
    public function fetchScalar($SQL, array $parameters = [])
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function fetchRow($SQL, array $parameters = [])
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function fetchAll($SQL, array $parameters = [])
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function rowCount($SQL, array $parameters = [])
    {
        return 0;
    }
}