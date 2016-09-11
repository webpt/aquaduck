<?php

namespace Webpt\Aquaduck;

use Exception;
use Webpt\Aquaduck\Exception\RuntimeException;

class FinalHandler
{
    private $errorMessage;
    private $errorCode;

    /**
     * FinalHandler constructor.
     * @param string $errorMessage
     * @param int $errorCode
     */
    public function __construct($errorMessage = "Unknown Error", $errorCode = 0)
    {
        $this->errorMessage = $errorMessage;
        $this->errorCode    = $errorCode;
    }

    /**
     * @param mixed $subject
     * @param \Exception|string $err
     * @return mixed|null
     * @throws \Webpt\Aquaduck\Exception\RuntimeException
     */
    public function __invoke($subject, $err = null)
    {
        if ($err) {
            $this->handleError($err);
            return null;
        }
        return $subject;
    }

    /**
     * @param \Exception|string $err
     * @return void
     * @throws \Webpt\Aquaduck\Exception\RuntimeException
     */
    private function handleError($err)
    {
        $message = $this->errorMessage;
        $code    = $this->errorCode;
        $prev    = null;

        if (is_string($err)) {
            $message = $err;
        }

        if ($err instanceof Exception) {
            $prev = $err;
        }

        throw new RuntimeException($message, $code, $prev);
    }
}
