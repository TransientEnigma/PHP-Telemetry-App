<?php
/**
 * UniversalLogger.php
 * For Logging
 * Author: J Masiano
 * Email: P2430705@my365.dmu.ac.uk
 * Date: 16/01/2021
 *
 * @author J Masiano <P2430705@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */

namespace Logging;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;


class UniversalLogger
{
    /**
     * UniversalLogger constructor.
     */
    public function __construct() { }
    public function __destruct() { }
    /**
     * @param $message
     */
    //Handles normal events
    public function logEvent($event)
    {
        $log = new Logger('Logger');
        $log->pushHandler(new StreamHandler(EVENTS_LOG_FILE, Logger::NOTICE));
        $log->notice(''.$event);
    }
    /**
     * @param $error
     */
    // Something is wrong and needs immediate attention
    public function logError($error)
    {
        $log = new Logger('Logger');
        $log->pushHandler(new StreamHandler(ERRORS_LOG_FILE, Logger::ERROR));
        $log->error(''.$error);
    }
    /**
     * @param $warning
     */
    // Take action before it becomes an error
    public function logWarning($warning)
    {
        $log = new Logger('Logger');
        $log->pushHandler(new StreamHandler(WARNINGS_LOG_FILE, Logger::WARNING));
        $log->warning(''.$warning);
    }
    /**
     * @param $critical
     */
    // E.g. when a system component is down
    public function logCritical($critical)
    {
        $log = new Logger('Logger');
        $log->pushHandler(new StreamHandler(CRITICAL_LOG_FILE, Logger::CRITICAL));
        $log->critical(''.$critical);
    }
}