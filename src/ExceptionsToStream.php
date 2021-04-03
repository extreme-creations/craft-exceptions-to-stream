<?php
/**
 * Exceptions To Stream plugin for Craft CMS 3.x
 *
 * A small plugin to capture all thrown exceptions to send to standard error stream
 *
 * @link      https://madebyextreme.com/
 * @copyright Copyright (c) 2021 Joe Pagan
 */

namespace extremecreations\exceptionstostream;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;

use craft\events\ExceptionEvent;
use craft\web\ErrorHandler;

use yii\base\Event;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use \Monolog\Formatter\LineFormatter;

/**
 * Class ExceptionsToStream
 *
 * @author    Joe Pagan
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
                $logger = new Logger();
                $lineFormatter = new LineFormatter();
                $lineFormatter->allowInlineLineBreaks();
                $lineFormatter->includeStacktraces();
                $logger->setFormatter($formatter);
                $logger->pushHandler(new StreamHandler('php://stderr', \Monolog\Logger::WARNING));
                $logger->critical($event->exception);
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
