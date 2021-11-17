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

use madebyextreme\exceptionstostream\services\LoggerService;

/**
 * Class ExceptionsToStream
 *
 * @author    Extreme
 * @package   ExceptionsToStream
 * @since     1.0.0
 * @property LoggerService $log
 *
 */
class ExceptionsToStream extends Plugin
{
    /**
     * @var ExceptionsToStream
     */
    public static $plugin;

    /**
     * @var mixed|object|null
     */
    private mixed $log;

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

        // Register service as a component
        $this->setComponents([
          'log' => LoggerService::class,
        ]);

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

                $this->log->handleException($exception);
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
