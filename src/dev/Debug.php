<?php
namespace umbalaconmeogia\phputil\dev;

/**
 * Usage of counting running time
 * <pre>
 * Debug::startCountTime('MY_COUNT'); // Start count time.
 * // do something
 * Debug::runtimeReport(self::TIME_COUNT_KEY); // Display running time.
 * </pre>
 */
class Debug
{
    public static $REPORT_COUNTER_FORMAT = 'Count: %d times';

    /**
     * @var int[][] $counter[key]
     */
    private static $counter = [];

    /**
     * @var float[] $countTime[key]
     */
    private static $countTime = [];

    /**
     * Remember current time specified by a key.
     * @param string $key
     * @return float current time.
     */
    public static function startCountTime($key = 'default')
    {
        self::$countTime[$key] = microtime(true);
        return self::$countTime[$key];
    }

    /**
     * Count time of registered key.
     * @param string $key
     * @return float diff time.
     */
    public static function countTime($key = 'default')
    {
        $time = microtime(true) - self::$countTime[$key];
        return $time;
    }

    /**
     * Display a running time.
     * @param string $key
     * @param string $messageFormat
     * @param boolean $echo If true, then echo the message.
     */
    public static function runtimeReport($key = 'default', $messageFormat = 'Time: %f seconds', $echo = TRUE)
    {
        $message = sprintf($messageFormat, self::countTime($key));
        if ($echo) {
            self::echo($message);
        }
        return $message;
    }

    /**
     * echo command with new line at the end.
     * @param string $message
     * @param string $end
     */
    public static function echo($message, $end = "\n")
    {
        echo "$message$end";
    }

    /**
     * Restart counter specified by a key.
     * @param string $key
     */
    public static function startCounter($key = 'default')
    {
        self::$counter[$key] = 0;
    }

    /**
     * Increase counter of speicified key.
     * @param int $unit
     * @param string $key
     * @param int|null $displayBatchSize If set, then display message if counter reach batch size.
     * @return float Number of counter.
     */
    public static function increaseCounter($displayBatchSize = NULL, $key = 'default', $unit = 1, $messageFormat = NULL)
    {
        self::$counter[$key] += $unit;
        if ($displayBatchSize && (self::$counter[$key] % $displayBatchSize == 0)) {
            self::reportCounter($key, $messageFormat);
        }
        return self::$counter[$key];
    }

    public static function getCounter($key = 'default')
    {
        return self::$counter[$key];
    }

    /**
     * Display a running time.
     * @param string $key
     * @param string $messageFormat
     * @param boolean $echo If true, then echo the message.
     */
    public static function reportCounter($key = 'default', $messageFormat = NULL, $echo = TRUE)
    {
        $messageFormat = $messageFormat ?? self::$REPORT_COUNTER_FORMAT;
        $message = sprintf($messageFormat, self::$counter[$key]);
        if ($echo) {
            self::echo($message);
        }
        return $message;
    }
}