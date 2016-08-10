<?php

namespace Phuria\QueryBuilder\Expression;

/**
 * @author Beniamin Jonatan Šimko <spam@simko.it>
 */
class SumExpression implements ExpressionInterface
{
    /**
     * @var ExpressionInterface $wrappedExpression
     */
    private $wrappedExpression;

    /**
     * @param ExpressionInterface $expression
     */
    public function __construct(ExpressionInterface $expression)
    {
        $this->wrappedExpression = $expression;
    }

    /**
     * @inheritdoc
     */
    public function compile()
    {
        return 'SUM(' . $this->wrappedExpression->compile() . ')';
    }
}