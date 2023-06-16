<?php

namespace Jauntin\CyberSource\Api;

use Throwable;

/**
 * @final
 */
class ErrorResponse
{
    public string $message;
    public int $statusCode;
    public Throwable $previous;

    public function fromThrowable(Throwable $e): self
    {
        $this->message = $e->getMessage();
        $this->statusCode = $e->getCode();
        $this->previous = $e;
        return $this;
    }
}
