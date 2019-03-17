<?php

declare(strict_types=1);

namespace Xpl\DateTime;

use DateTimeInterface;
use DateTimeImmutable;

/**
 * DateTime utilities
 */
abstract class Date
{

	/**
	 * A full numeric representation of a year, 4 digits
	 */
	const FORMAT_YEAR = 'Y';

	/**
	 * Numeric representation of a month, without leading zeros (integer, 1 through 12)
	 */
	const FORMAT_MONTH = 'n';

	/**
	 * Day of the month without leading zeros (integer, 1 through 31)
	 */
	const FORMAT_DAY = 'j';

	/**
	 * Numeric representation of the day of the week, starting on Sunday (integer, 0 through 6)
	 */
	const FORMAT_DAY_OF_WEEK = 'w';

	/** 
	 * The day of the year (integer, 0 through 365)
	 */
	const FORMAT_DAY_OF_YEAR = 'z';

	/**
	 * The number of days in the month (integer, 28 through 31)
	 */
	const FORMAT_DAYS_IN_MONTH = 't';

	/**
	 * A full textual representation of the day of the week
	 */
	const FORMAT_DAY_NAME = 'l';

	/**
	 * A textual representation of a day, three letters
	 */
	const FORMAT_DAY_NAME_SHORT = 'D';

	/**
	 * A full textual representation of a month
	 */
	const FORMAT_MONTH_NAME = 'F';

	/**
	 * A short textual representation of a month, three letters
	 */
	const FORMAT_MONTH_NAME_SHORT = 'M';

	/**
	 * Timezone offset in seconds. The offset for timezones west of UTC is 
	 * always negative, and for those east of UTC is always positive.
	 */
	const FORMAT_TIMEZONE_OFFSET = 'Z';

	/**
	 * Whether or not the date is in daylight saving time (integer, 0 or 1)
	 */
	const FORMAT_DAYLIGHT_SAVINGS = 'I';

	/**
	 * ISO-8601 week number of year, weeks starting on Monday
	 */
	const ISO8601_WEEK = 'W';

	/**
	 * Returns a DateTimeImmutable for a potentially mutable DateTimeInterface
	 *
	 * @param DateTimeInterface $datetime
	 *
	 * @return DateTimeImmutable
	 */
	public static function toImmutable(DateTimeInterface $datetime): DateTimeImmutable
	{
		return $datetime instanceof DateTimeImmutable 
			? $datetime
			: DateTimeImmutable::createFromMutable($datetime);
	}

	/**
	 * Returns the year as a 4-digit integer.
	 * 
	 * @param DateTimeInterface $datetime
	 *
	 * @return int
	 */
	public static function year(DateTimeInterface $datetime): int
	{
		return (int)$datetime->format(self::FORMAT_YEAR);
	}

	/**
	 * Returns the month as a 1- or 2-digit integer.
	 *
	 * @param DateTimeInterface $datetime
	 *
	 * @return int 1 through 12
	 */
	public static function month(DateTimeInterface $datetime): int
	{
		return (int)$datetime->format(self::FORMAT_MONTH);
	}

	/**
	 * Returns the day as a 1- or 2-digit integer.
	 *
	 * @param DateTimeInterface $datetime
	 *
	 * @return int 1 through 31
	 */
	public static function day(DateTimeInterface $datetime): int
	{
		return (int)$datetime->format(self::FORMAT_DAY);
	}

	/**
	 * Returns the day of the week as a 1-digit integer.
	 * 
	 * @param DateTimeInterface $datetime
	 *
	 * 0 for Sunday, 1 for Monday, ..., 6 for Saturday
	 *
	 * @return int
	 */
	public static function dayOfWeek(DateTimeInterface $datetime): int
	{
		return (int)$datetime->format(self::FORMAT_DAY_OF_WEEK);
	}

	/**
	 * Returns the day of the year as a 1-, 2-, or 3-digit integer.
	 *
	 * @param DateTimeInterface $datetime
	 *
	 * @return int 0 through 365
	 */
	public static function dayOfYear(DateTimeInterface $datetime): int
	{
		return (int)$datetime->format(self::FORMAT_DAY_OF_YEAR);
	}

	/**
	 * Returns the number of days in the month as a 2-digit integer.
	 *
	 * @param DateTimeInterface $datetime
	 *
	 * @return int 28 through 31
	 */
	public static function daysInMonth(DateTimeInterface $datetime): int
	{
		return $datetime->format(self::FORMAT_DAYS_IN_MONTH);
	}

	/**
	 * Returns the full name of the day of the week (e.g. "Sunday").
	 *
	 * @param DateTimeInterface $datetime
	 *
	 * @return string
	 */
	public static function dayName(DateTimeInterface $datetime): string
	{
		return $datetime->format(self::FORMAT_DAY_NAME);
	}

	/**
	 * Returns the full name of the month (e.g. "January").
	 *
	 * @param DateTimeInterface $datetime
	 *
	 * @return string
	 */
	public static function monthName(DateTimeInterface $datetime): string
	{
		return $datetime->format(self::FORMAT_MONTH_NAME);
	}

	/**
	 * Returns the date/time timezone offset from GMT.
	 *
	 * @param DateTimeInterface $datetime
	 *
	 * @return int
	 */
	public static function timezoneOffset(DateTimeInterface $datetime): int
	{
		return (int)$datetime->format(self::FORMAT_TIMEZONE_OFFSET);
	}

	/**
	 * Whether the date is a weekday (Monday through Friday).
	 *
	 * @param DateTimeInterface $datetime
	 *
	 * @return bool
	 */
	public static function isWeekday(DateTimeInterface $datetime): bool
	{
		$dow = self::dayOfWeek($datetime);

		return $dow != 0 && $dow != 6;
	}

	/**
	 * Whether the date is in daylight savings.
	 *
	 * @param DateTimeInterface $datetime
	 *
	 * @return bool
	 */
	public static function isDaylightSavings(DateTimeInterface $datetime): bool
	{
		return $datetime->format(self::FORMAT_DAYLIGHT_SAVINGS) == 1;
	}

}