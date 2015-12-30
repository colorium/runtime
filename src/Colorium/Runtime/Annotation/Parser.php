<?php

namespace Colorium\Runtime\Annotation;

interface Parser
{

    /**
     * Parse docblock
     *
     * @param string $docblock
     * @return array
     */
    public function parse($docblock);

}