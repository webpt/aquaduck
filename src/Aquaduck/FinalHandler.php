<?php

namespace Webpt\Aquaduck;

use Exception;
use Webpt\Aquaduck\Exception\RuntimeException;

class FinalHandler
{
    private $errorMessage;
    private $errorCode;

    public function __construct($errorMessage = "Unknown Error", $errorCode = 0)
    {
        $this->errorMessage = $errorMessage;
        $this->errorCode    = $errorCode;
    }

    public function __invoke($subject, $err = null)
    {
        if ($err) {
            return $this->handleError($err, $subject);
        }
        return $subject;
    }

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
