<?php
/**
 * Exceptions To Stream plugin for Craft CMS 3.x
 *
 * A small plugin to capture thrown exceptions (excluding status codes in the 400 range) to send to standard error stream
 *
 * @link      https://madebyextreme.com/
 * @copyright Copyright (c) 2021 Extreme
 */

namespace madebyextreme\exceptionstostream;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;

use craft\events\ExceptionEvent;
use craft\web\ErrorHandler;

use yii\base\Event;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

/**
 * Class ExceptionsToStream
 *
 * @author    Extreme
 * @package   ExceptionsToStream
 * @since     1.0.0
 *
 */
class ExceptionsToStream extends Plugin
{
    /**
     * @var ExceptionsToStream
     */
    public static $plugin;

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

    /**
     * @var bool
     */
    public $hasCpSettings = false;

    /**
     * @var bool
     */
    public $hasCpSection = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            ErrorHandler::className(),
            ErrorHandler::EVENT_BEFORE_HANDLE_EXCEPTION,
            function(ExceptionEvent $event) {
                Craft::debug(
                    'ErrorHandler::EVENT_BEFORE_HANDLE_EXCEPTION',
                    __METHOD__
                );
                $exception = $event->exception;

                // If this is a Twig Runtime exception, use the previous one instead
                if ($exception instanceof \Twig\Error\RuntimeError &&
                    ($previousException = $exception->getPrevious()) !== null) {
                    $exception = $previousException;
                }

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
                $stream = new StreamHandler('php://stderr', \Monolog\Logger::WARNING);
                // Set the formatter for the stream
                $stream->setFormatter($formatter);
                // Push the stream to logger
                $logger->pushHandler($stream);
                // Send all exceptions to logger
                $logger->critical($exception);
            }
        );

        Craft::info(
            Craft::t(
                'exceptions-to-stream',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }
}
