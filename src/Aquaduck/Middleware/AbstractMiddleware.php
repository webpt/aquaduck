<?php

namespace Webpt\Aquaduck\Middleware;

abstract class AbstractMiddleware implements MiddlewareInterface
{
    const ORDER_PREPEND = 'prepend';
    const ORDER_APPEND = 'append';

    protected $order = 'append';

    /**
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return bool
     */
    public function isPrepend()
    {
        return ($this->getOrder() === static::ORDER_PREPEND);
    }

    /**
     * @return bool
     */
    public function isAppend()
    {
        return ($this->getOrder() === static::ORDER_APPEND);
    }

    /**
     * @param mixed $subject
     * @param callable $next
     * @return mixed
     */
    public function __invoke($subject, callable $next = null)
    {
        if ($next && $this->isPrepend()) {
            $subject = $next($subject);
        }

        $transformed = $this->execute($subject);

        if ($next && $this->isAppend()) {
            return $next($transformed);
        }

        return $transformed;
    }

    /**
     * @param mixed $subject
     * @return mixed
     */
    abstract protected function execute($subject);
}
