<?php

namespace Colorium\Runtime\Contract;

interface ParserInterface
{

    /**
     * Parse docblock
     *
     * @param string $docblock
     * @return array
     */
    public function parse($docblock);

}