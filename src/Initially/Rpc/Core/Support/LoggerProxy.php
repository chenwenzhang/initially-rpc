<?php
namespace Initially\Rpc\Core\Support;

use Psr\Log\LoggerInterface;

class LoggerProxy implements LoggerInterface
{

    /**
     * @var LoggerInterface
     */
    private static $logger;

    /**
     * @var LoggerProxy
     */
    private static $instance;

    /**
     * Set logger
     *
     * @param LoggerInterface $logger
     */
    public static function setLogger(LoggerInterface $logger)
    {
        self::$logger = $logger;
    }

    /**
     * Get instance
     *
     * @return LoggerProxy
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new LoggerProxy();
        }

        return self::$instance;
    }

    /**
     * Is set logger
     *
     * @return bool
     */
    private static function isSetLogger()
    {
        return isset(self::$logger);
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @return bool
     */
    public function emergency($message, array $context = array())
    {
        if (self::isSetLogger()) {
            return self::$logger->emergency($message, $context);
        }

        return false;
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     * @return bool
     */
    public function alert($message, array $context = array())
    {
        if (self::isSetLogger()) {
            return self::$logger->alert($message, $context);
        }

        return false;
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     * @return bool
     */
    public function critical($message, array $context = array())
    {
        if (self::isSetLogger()) {
            return self::$logger->critical($message, $context);
        }

        return false;
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @return bool
     */
    public function error($message, array $context = array())
    {
        if (self::isSetLogger()) {
            return self::$logger->error($message, $context);
        }

        return false;
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     * @return bool
     */
    public function warning($message, array $context = array())
    {
        if (self::isSetLogger()) {
            return self::$logger->warning($message, $context);
        }

        return false;
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     * @return bool
     */
    public function notice($message, array $context = array())
    {
        if (self::isSetLogger()) {
            return self::$logger->notice($message, $context);
        }

        return false;
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     * @return bool
     */
    public function info($message, array $context = array())
    {
        if (self::isSetLogger()) {
            return self::$logger->info($message, $context);
        }

        return false;
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @return bool
     */
    public function debug($message, array $context = array())
    {
        if (self::isSetLogger()) {
            return self::$logger->debug($message, $context);
        }

        return false;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return bool
     */
    public function log($level, $message, array $context = array())
    {
        if (self::isSetLogger()) {
            return self::$logger->log($level, $message, $context);
        }

        return false;
    }

    /**
     * @param $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, array $arguments)
    {
        if (self::isSetLogger() && method_exists(self::$logger, $name)) {
            return call_user_func_array(array(self::$logger, $name), $arguments);
        }
    }

}