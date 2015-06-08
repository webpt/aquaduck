<?php
/**
 *
 *
 * @copyright Copyright (c) 2015 WebPT, INC
 */
namespace Webpt\Aquaduck;

interface AquaduckInterface
{
    /**
     * @param mixed $subject
     * @param callable $out
     * @return mixed
     */
    public function __invoke($subject, $out = null);

    /**
     * @param HandlerInterface|callable $middleware
     * @param int $priority
     * @return AquaduckInterface
     */
    public function bind($middleware, $priority = 1);
}