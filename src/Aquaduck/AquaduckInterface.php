<?php

namespace Webpt\Aquaduck;

interface AquaduckInterface
{
    /**
     * @param mixed $subject
     * @param callable $out
     * @return mixed
     */
    public function __invoke($subject, $out = null);
}
