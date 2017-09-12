<?php

namespace AppBundle\Doctrine\Query;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Class OverlapsFunction.
 *
 * Usage: overlaps(daterange1, daterange2): bool
 */
class OverlapsFunction extends FunctionNode
{
    /** @var Node */
    private $firstPeriodExpression;
    /** @var Node */
    private $secondPeriodExpression;

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER); // finding "overlaps"
        $parser->match(Lexer::T_OPEN_PARENTHESIS); // moving to open parenthesis
        $this->firstPeriodExpression = $parser->StringPrimary(); // parsing first value
        $parser->match(Lexer::T_COMMA); // moving to comma
        $this->secondPeriodExpression = $parser->StringPrimary(); // parsing second value
        $parser->match(Lexer::T_CLOSE_PARENTHESIS); // expression is closed
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        return sprintf(
            '%s && %s', // daterange('2017-01-01', '2018-01-01') && daterange('2017-04-01', '2017-05-01')
            $this->firstPeriodExpression->dispatch($sqlWalker),
            $this->secondPeriodExpression->dispatch($sqlWalker)
        );
    }
}




