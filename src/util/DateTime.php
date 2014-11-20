<?php
/**
 * php-batsg-util: PHP utility classes.
 * Copyright (c) <2014> Tran Trung Thanh <umbalaconmeogia@gmail.com>
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @package Batsg\Util
 * @author Tran Trung Thanh <umbalaconmeogia@gmail.com>
 * @copyright 2014 Tran Trung Thanh <umbalaconmeogia@gmail.com>
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/umbalaconmeogia/batsg-php-util
 */
namespace Batsg\Util;

/**
 * Manipulate date time data.
 */
class DateTime
{
  const FORMAT_DATETIME = 'Y-m-d H:i:s';
  const FORMAT_DATE = 'Y-m-d';
  const FORMAT_TIME = 'H:i:s';

  const WDAY_SUN = 0;
  const WDAY_MON = 1;
  const WDAY_TUE = 2;
  const WDAY_WED = 3;
  const WDAY_THU = 4;
  const WDAY_FRI = 5;
  const WDAY_SAT = 6;

  /**
   * @var int
   */
  private $_year;

  /**
   * @var int
   */
  private $_month;

  /**
   * @var int
   */
  private $_day;

  /**
   * @var int
   */
  private $_hour;

  /**
   * @var int
   */
  private $_minute;

  /**
   * @var int
   */
  private $_second;

  /**
   * @var int
   */
  private $_wday;

  /**
   * @var int
   */
  private $_timestamp;

  /**
   * Create an DateTime object from a string that represents date time.
   *
   * Date format that may be used:
   * - mm/dd/yyyy - 02/01/2014
   * - mm/dd/yy   - 02/01/14
   * - yyyy/mm/dd  - 2014/02/01
   * - dd-mm-yyyy - 01-02-2014
   * - yy-mm-dd   - 14-02-01
   * - yyyy-mm-dd - 2014-02-01
   *
   * @param string $dateTime Source date time string.
   *   $dateTime may be "year/month/day" or "year/month/day hour:minute" or "year/month/day hour:minute:second"
   * @return DateTime
   */
  public static function createFromString($dateTime)
  {
    // Get the approciate timestamp.
    $timestamp = strtotime($dateTime);
    // Get the DateTime instance.
    return new DateTime($timestamp);
  }

  /**
   * Create an DateTime object from date time elements.
   *
   * @param int $year
   * @param int $month
   * @param int $day
   * @param int $hour
   * @param int $minute
   * @param int $second
   * @return DateTime
   */
  public static function createFromYmdHms($year, $month, $day, $hour = 0, $minute = 0, $second = 0)
  {
    // Convert to timestamp.
    $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
    // Set from timestamp to date time elements.
    return new DateTime($timestamp);
  }

  /**
   * Create an DateTime instance from a timestamp.
   *
   * @param int $timestamp
   * @return DateTime
   */
  public static function createFromTimestamp($timestamp)
  {
    return new DateTime($timestamp);
  }

  /**
   * Create an DateTime object that represents current date time.
   * @return DateTime
   */
  public static function now()
  {
    return new DateTime(time());
  }

  /**
   * Constructor.
   *
   * @param int|string|DateTime $timestamp
   */
  public function __construct($timestamp)
  {
    // Convert $timestamp to numeric.
    $dateTime = $timestamp;
    if (!is_numeric($dateTime)) {
      if (is_string($dateTime)) {
        $dateTime = DateTime::createFromString($dateTime);
      }
      if ($dateTime instanceof DateTime) {
        $dateTime = $dateTime->getTimestamp();
      }
    }
    if (is_numeric($dateTime)) {
      $this->resetByTimestamp($dateTime);
    } else {
      throw new \Exception("Don't recognize date time of " . var_export($timestamp));
    }
  }

  /**
   * Reset the date time elements of the object by specified timestamp.
   *
   * @param int $timestamp
   * @return DateTime $this object.
   */
  public function resetByTimestamp($timestamp)
  {
    $this->_timestamp = $timestamp;
    // Get date time elements.
    $element = getdate($timestamp);
    $this->_year = $element['year'];
    $this->_month = $element['mon'];
    $this->_day = $element['mday'];
    $this->_hour = $element['hours'];
    $this->_minute = $element['minutes'];
    $this->_second = $element['seconds'];
    $this->_wday = $element['wday'];
    return $this;
  }

  /**
   * Reset the date time elements of the object.
   *
   * Parameters may present an invalid date time, such as 2009-09-32 01:52:31.
   * It will be convert to appropriate value (2009-10-02 01:52:31).
   *
   * @param int $year
   * @param int $month
   * @param int $day
   * @param int $hour
   * @param int $minute
   * @param int $second
   * @return DateTime $this object.
   */
  public function reset($year, $month, $day, $hour, $minute, $second)
  {
    // Convert to timestamp.
    $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
    // Set from timestamp to date time elements.
    return $this->resetByTimeStamp($timestamp);
  }

  /**
   * Get year value
   * @return int
   */
  public function getYear() {
    return $this->_year;
  }

  /**
   * Get month value
   * @return int
   */
  public function getMonth() {
    return $this->_month;
  }

  /**
   * Get day value
   * @return int
   */
  public function getDay() {
    return $this->_day;
  }

  /**
   * Get hour value
   * @return int
   */
  public function getHour() {
    return $this->_hour;
  }

  /**
   * Get minute value
   * @return int
   */
  public function getMinute() {
    return $this->_minute;
  }

  /**
   * Get second value
   * @return int
   */
  public function getSecond()
  {
    return $this->_second;
  }

  /**
   * Get week day value
   * @return int from 0 (Sunday) to 6 (Saturday).
   */
  public function getWDay()
  {
    return $this->_wday;
  }

  /**
   * Get timestamp value
   * @return int
   */
  public function getTimestamp()
  {
    return $this->_timestamp;
  }

  /**
   * @param string $format The format string as used in date().
   * @return string
   */
  public function __toString()
  {
    return $this->toString();
  }

  /**
   * @param string $format The format string as used in date().
   * @return string
   */
  public function toString($format = self::FORMAT_DATETIME)
  {
    return date($format, $this->_timestamp);
  }

  /**
   * Return an DateTime object that represents the first day of month
   * that current instance represents.
   *
   * @return DateTime
   */
  public function firstDayOfMonth()
  {
    return self::createFromYmdHms($this->_year, $this->_month, 1);
  }

  /**
   * Return an DateTime object that represents the last day of month
   * that current instance represents.
   *
   * @return DateTime
   */
  public function lastDayOfMonth()
  {
    return self::createFromYmdHms($this->_year, $this->_month + 1, 0);
  }

  /**
   * Get a DateTime object with the date part from current object (time part is zero).
   * @return DateTime
   */
  public function getDate()
  {
    return self::createFromYmdHms($this->_year, $this->_month, $this->_day);
  }

  /**
   * Create an DateTime object by increment or decrement date time element
   * from the current object.
   * The parameter may be positive or negative interger.
   *
   * @param int $year
   * @param int $month
   * @param int $day
   * @param int $hour
   * @param int $minute
   * @param int $second
   * @param bool $modify Modify the object itself if is set to TRUE.
   * @return DateTime
   */
  public function add($year, $month, $day, $hour, $minute, $second, $modify = FALSE)
  {
    $year = $this->_year + $year;
    $month = $this->_month + $month;
    $day = $this->_day + $day;
    $hour = $this->_hour + $hour;
    $minute = $this->_minute + $minute;
    $second = $this->_second + $second;
    $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
    if ($modify) {
      $hDateTime = $this->resetByTimestamp($timestamp);
    } else {
      $hDateTime = new DateTime($timestamp);
    }
    return $hDateTime;
  }

  /**
   * Create an DateTime object by increase/decrease several years from
   * the current object.
   *
   * @param int $n
   * @param bool $modify Modify the object itself if is set to TRUE.
   * @return DateTime
   */
  public function nextNYear($n, $modify = FALSE)
  {
    return $this->add($n, 0, 0, 0, 0, 0, $modify);
  }

  /**
   * Create an DateTime object by increase/decrease several months from
   * the current object.
   *
   * @param int $n
   * @param bool $modify Modify the object itself if is set to TRUE.
   * @return DateTime
   */
  public function nextNMonth($n, $modify = FALSE)
  {
    return $this->add(0, $n, 0, 0, 0, 0, $modify);
  }

  /**
   * Create an DateTime object by increase/decrease several days from
   * the current object.
   *
   * @param int $n
   * @param bool $modify Modify the object itself if is set to TRUE.
   * @return DateTime
   */
  public function nextNDay($n, $modify = FALSE)
  {
    return $this->add(0, 0, $n, 0, 0, 0, $modify);
  }

  /**
   * Create an DateTime object by increase/decrease several hours from
   * the current object.
   *
   * @param int $n
   * @param bool $modify Modify the object itself if is set to TRUE.
   * @return DateTime
   */
  public function nextNHour($n, $modify = FALSE)
  {
    return $this->add(0, 0, 0, $n, 0, 0, $modify);
  }

  /**
   * Create an DateTime object by increase/decrease several minutes from
   * the current object.
   *
   * @param int $n
   * @param bool $modify Modify the object itself if is set to TRUE.
   * @return DateTime
   */
  public function nextNMinute($n, $modify = FALSE)
  {
    return $this->add(0, 0, 0, 0, $n, 0, $modify);
  }

  /**
   * Create an DateTime object by increase/decrease several seconds from
   * the current object.
   *
   * @param int $n
   * @param bool $modify Modify the object itself if is set to TRUE.
   * @return DateTime
   */
  public function nextNSecond($n, $modify = FALSE)
  {
    return $this->add(0, 0, 0, 0, 0, $n, $modify);
  }
}
?>