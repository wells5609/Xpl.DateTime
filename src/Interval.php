<?php

declare(strict_types=1);

namespace Xpl\DateTime;

use DateTime;
use DatePeriod;
use DateInterval;
use Xpl\DateTime\Exception\{
	RuntimeException,
	InvalidArgumentException
};

/**
 * DateInterval utilities
 */
abstract class Interval
{

	const SPEC_YEARS	= 'Y';
	const SPEC_MONTHS	= 'M';
	const SPEC_DAYS		= 'D';
	const SPEC_WEEKS	= 'W';
	const SPEC_HOURS	= 'H';
	const SPEC_MINUTES	= 'M';
	const SPEC_SECONDS	= 'S';

	const SECONDS_PER_MINUTE	= 60;
	const SECONDS_PER_HOUR		= self::SECONDS_PER_MINUTE * 60;
	const SECONDS_PER_DAY		= self::SECONDS_PER_HOUR * 24;
	const SECONDS_PER_WEEK		= self::SECONDS_PER_DAY * 7;
	const MINUTES_PER_HOUR		= 60;
	const MINUTES_PER_DAY		= self::MINUTES_PER_HOUR * 24;
	const MINUTES_PER_WEEK		= self::MINUTES_PER_DAY * 7;
	const HOURS_PER_DAY			= 24;
	const HOURS_PER_WEEK		= self::HOURS_PER_DAY * 7;

	/**
	 * Returns a DateInterval from an int, string, or existing instance.
	 *
	 * @throws InvalidArgumentException if $interval is not a string, int, or DateInterval
	 * 
	 * @param DateInterval|string|int $interval If string, interpreted as a
	 * 		relative date string using DateInterval::createFromDateString(). If
	 *		int, interpreted as the number of days in the interval.
	 * 		
	 * @return DateInterval
	 */
	public static function of($interval): DateInterval
	{
		if ($interval instanceof DateInterval) {
			return $interval;
		} else if (is_int($interval)) {
			return new DateInterval("P{$interval}D");
		} else if (is_string($interval)) {
			return DateInterval::createFromDateString($interval);
		}

		throw new InvalidArgumentException(sprintf(
			"Interval must be DateInterval, integer, or string, given: %s",
			is_object($interval) ? get_class($interval) : gettype($interval)
		));
	}

	/**
	 * Returns an int representing the interval in seconds
	 * 
	 * @throws RuntimeException if the interval has non-zero years or months, as these are ambiguous
	 *
	 * @param DateInterval $interval
	 * 
	 * @return int
	 */
	public static function toSeconds(DateInterval $interval): int
	{
		if ($interval->days !== false) {
			// use days when available
			$sec = $interval->days * self::SECONDS_PER_DAY;
		} else {
			if ($interval->y || $interval->m) {
				throw new RuntimeException("Cannot get interval in seconds: years and months are ambiguous");
			}
			$sec = $interval->d * self::SECONDS_PER_DAY;
		}

		$sec += ($interval->h * self::SECONDS_PER_HOUR)
			+ ($interval->i * self::SECONDS_PER_MINUTE)
			+ $interval->s;
		
		return $sec;
	}

	/**
	 * Returns a float representing the interval in seconds with microseconds
	 *
	 * @param DateInterval $interval
	 *
	 * @return float
	 */
	public static function toMicroseconds(DateInterval $interval): float
	{
		return self::toSeconds($interval) + $interval->f;
	}

	/**
	 * Compares two DateIntervals based on their value in seconds.
	 *
	 * NOTE: This method does not take into account the $invert property, so
	 * two intervals with the same value in seconds where only 1 is inverted
	 * will compare as equal!
	 * 
	 * @param DateInterval $a
	 * @param DateInterval $b
	 *
	 * @return int
	 */
	public static function compare(DateInterval $a, DateInterval $b): int
	{
		return self::toSeconds($a) <=> self::toSeconds($b);
	}

	/**
	 * Checks whether the intervals are equal based on their value in seconds
	 *
	 * @param DateInterval $a
	 * @param DateInterval $b
	 *
	 * @return bool
	 */
	public static function areEqual(DateInterval $a, DateInterval $b): bool
	{
		try {
			return self::toSeconds($a) === self::toSeconds($b)
				&& $a->invert === $b->invert;
		} catch (\Throwable $e) {
			// pass thru
		}

		return false;
	}

	/**
	 * @return int
	 */
	public static function getHash(DateInterval $interval): int
	{
		return self::toSeconds($interval) ?: 0;
	}

	/**
	 * Builds an interval specification as per ISO 8601
	 * 
	 * @link http://php.net/manual/en/dateinterval.construct.php
	 * 
	 * @throws RuntimeException if both $weeks and $days are non-zero
	 *
	 * @param int $years
	 * @param int $months
	 * @param int $weeks
	 * @param int $days
	 * @param int $hours
	 * @param int $minutes
	 * @param int $seconds
	 *
	 * @return string
	 */
	public static function spec(
		int $years = 0, 
		int $months = 0, 
		int $weeks = 0,
		int $days = 0, 
		int $hours = 0,
		int $minutes = 0,
		int $seconds = 0
	): string
	{
		if ($weeks && $days) {
			throw new RuntimeException("Cannot build spec using both weeks and days");
		}

		$spec = 'P';

		($years > 0)	and $spec .= "{$years}Y";
		($months > 0)	and $spec .= "{$months}M";
		($weeks > 0)	and $spec .= "{$weeks}W";
		($days > 0)		and $spec .= "{$days}D";

		if ($hours || $minutes || $seconds) {
			$spec .= 'T';
			($hours > 0)	and $spec .= "{$hours}H";
			($minutes > 0)	and $spec .= "{$minutes}M";
			($seconds > 0)	and $spec .= "{$seconds}S";
		}

		return $spec;
	}

}