<?php

namespace Src\Infra\Exceptions;

use Exception;

class HttpException extends Exception
{
    public function render()
    {
        return response()->json([
            'statusCode' => $this->getCode(),
            'message' => $this->getMessage(),
        ], $this->getCode());
    }
}
