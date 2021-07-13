<?php

namespace madebyextreme\services;

use yii\base\Component;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class LoggerService extends Component
{

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

      // Create a Logger
      $logger = new Logger('app');
      // Create a LineFormatter
      $formatter = new LineFormatter();
      // Allow inline line breaks
      $formatter->allowInlineLineBreaks();
      // Include stack traces
      $formatter->includeStacktraces();
      // Create a stream
      $stream = new StreamHandler('php://stderr', Logger::ERROR);
      // Set the formatter for the stream
      $stream->setFormatter($formatter);
      // Push the stream to logger
      $logger->pushHandler($stream);
      // Send all exceptions to logger
      $logger->error($exception);
  }

  /**
   * Send a string of any time to the output stream
   *
   * @param string $message
   * @return void
   */
  public function log(string $message)
  {
      // Create a Logger
      $logger = new Logger('app');
      // Create a LineFormatter
      $formatter = new LineFormatter();
      // Allow inline line breaks
      $formatter->allowInlineLineBreaks();
      // Include stack traces
      $formatter->includeStacktraces();
      // Create a stream
      $stream = new StreamHandler('php://stdout', Logger::INFO);
      // Set the formatter for the stream
      $stream->setFormatter($formatter);
      // Push the stream to logger
      $logger->pushHandler($stream);
      // Send all exceptions to logger
      $logger->info($message);
  }

}
