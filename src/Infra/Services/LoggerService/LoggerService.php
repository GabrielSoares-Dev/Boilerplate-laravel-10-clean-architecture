<?php

namespace Src\Infra\Services\LoggerService;

use Illuminate\Support\Facades\Log;
use Src\Application\Services\LoggerServiceInterface;

class LoggerService implements LoggerServiceInterface
{
    private string $context;

    public function __construct()
    {
        $this->context = env('APP_NAME');
    }

    private function generateMessage(string $message): string
    {
        return "$this->context $message";
    }

    public function debug(string $message, ?object $input = null): void
    {
        Log::debug($this->generateMessage($message), (array) $input);
    }

    public function info(string $message, ?object $input = null): void
    {
        Log::info($this->generateMessage($message), (array) $input);
    }

    public function error(string $message, ?object $input = null): void
    {
        Log::error($this->generateMessage($message), (array) $input);
    }

    public function notice(string $message, ?object $input = null): void
    {
        Log::notice($this->generateMessage($message), (array) $input);
    }

    public function warning(string $message, ?object $input = null): void
    {
        Log::warning($this->generateMessage($message), (array) $input);
    }

    public function critical(string $message, ?object $input = null): void
    {
        Log::critical($this->generateMessage($message), (array) $input);
    }

    public function alert(string $message, ?object $input = null): void
    {
        Log::alert($this->generateMessage($message), (array) $input);
    }

    public function emergency(string $message, ?object $input = null): void
    {
        Log::alert($this->generateMessage($message), (array) $input);
    }
}
