<?php

namespace madebyextreme\exceptionstostream\services;

use yii\base\Component;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class LoggerService extends Component
{
    private $logger;

    private function setupLogger($message, $stream)
    {
        // Create a Logger
        $this->logger = new Logger('app');
        // Create a LineFormatter
        $formatter = new LineFormatter();
        // Allow inline line breaks
        $formatter->allowInlineLineBreaks();
        // Include stack traces
        $formatter->includeStacktraces();
        // Set the formatter for the stream
        $stream->setFormatter($formatter);
        // Push the stream to logger
        $this->logger->pushHandler($stream);
    }
    /**
     * debug log
     *
     * @param string $message
     * @return void
     */
    public function debug(string $message)
    {
        $stream = new StreamHandler('php://stdout' , Logger::DEBUG);
        $this->setupLogger($message, $stream);
        $this->logger->debug($message);
    }

    /**
     * info log
     *
     * @param string $message
     * @return void
     */
    public function info(string $message)
    {
        $stream = new StreamHandler('php://stdout' , Logger::INFO);
        $this->setupLogger($message, $stream);
        $this->logger->info($message);
    }

    /**
     * notice log
     *
     * @param string $message
     * @return void
     */
    public function notice(string $message)
    {
        $stream = new StreamHandler('php://stdout' , Logger::NOTICE);
        $this->setupLogger($message, $stream);
        $this->logger->notice($message);
    }

    /**
     * warning log
     *
     * @param string $message
     * @return void
     */
    public function warning(string $message)
    {
        $stream = new StreamHandler('php://stdout' , Logger::WARNING);
        $this->setupLogger($message, $stream);
        $this->logger->warning($message);
    }

    /**
     * error log
     *
     * @param string $message
     * @return void
     */
    public function error(string $message)
    {
        $stream = new StreamHandler('php://stderr' , Logger::ERROR);
        $this->setupLogger($message, $stream);
        $this->logger->error($message);
    }

    /**
     * critical log
     *
     * @param string $message
     * @return void
     */
    public function critical(string $message)
    {
        $stream = new StreamHandler('php://stderr' , Logger::CRITICAL);
        $this->setupLogger($message, $stream);
        $this->logger->critical($message);
    }

    /**
     * alert log
     *
     * @param string $message
     * @return void
     */
    public function alert(string $message)
    {
        $stream = new StreamHandler('php://stderr' , Logger::ALERT);
        $this->setupLogger($message, $stream);
        $this->logger->alert($message);
    }

    /**
     * alert log
     *
     * @param string $message
     * @return void
     */
    public function emergency(string $message)
    {
        $stream = new StreamHandler('php://stderr' , Logger::EMERGENCY);
        $this->setupLogger($message, $stream);
        $this->logger->emergency($message);
    }

    /**
     * Handle an exception and send to error stream
     *
     * @param [type] $exception
     * @return void
     */
    public function handleException($exception)
    {
        // Get status code
        $statusCode = $exception->statusCode ?? null;

        // Check if we should skip status code
        if (preg_match('/4[0-9][0-9]/', $statusCode)) {
            return;
        }

        // Send all exceptions to logger
        $this->critical($exception);
    }

}
