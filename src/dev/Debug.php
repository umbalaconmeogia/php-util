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

    public static function die($message, $end = "\n")
    {
        self::echo($message, $end);
        die;
    }

    public static function print_r($data, $die = TRUE)
    {
        self::echo(print_r($data, TRUE));
        if ($die) {
            die;
        }
    }

    /**
     *
     * Usage example
     * ```php
     *    Debug::dieCondition($sfProductCycle, ['team_code' => '211000', 'buyer_code' => '000101', 'product_code' => '010402'],
     *              "result = $result,");
     * ```
     * @param Object $conditionObj
     * @param array $condition Mapping between object attribute and value.
     * @param array|string $message
     */
    public static function dieCondition($conditionObj, $condition, $message = NULL)
    {
        $checkCondition = TRUE;
        foreach ($condition as $key => $value) {
            $checkCondition = $checkCondition && ($conditionObj->$key == $value);
        }
        if ($checkCondition) {
            if ($message == NULL) {
                $message = $condition;
            }
            if (is_callable($message)) {
                $message = call_user_func($message);
            } else if (is_array($message)) {
                $str = [];
                foreach ($message as $key => $value) {
                    $str[] = "$key = $value";
                }
                $message = join(', ', $str);
            }

            Debug::die($message);
        }
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

    /**
     * @param string $fullClassName
     * @return string
     */
    public static function shortClassName($fullClassName)
    {
        $paths = explode('\\', $fullClassName);
        return $paths[count($paths) - 1];
    }
}