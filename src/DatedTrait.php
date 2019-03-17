<?php

declare(strict_types=1);

namespace Xpl\DateTime;

use DateTimeInterface;
use DateTimeImmutable;
use Xpl\DateTime\Exception\RuntimeException;

/**
 * Used to compose classes that have a DateTime associated with each instance.
 */
trait DatedTrait
{
	
	/**
	 * The associated date/time
	 * 
	 * @var DateTimeImmutable
	 */
	private $datetime;

	/**
	 * Sets the associated date/time.
	 * 
	 * @throws RuntimeException if the date is already set
	 *
	 * @param DateTimeInterface $datetime
	 */
	public function setDateTime(DateTimeInterface $datetime)
	{
		if (isset($this->datetime)) {
			throw new RuntimeException("Cannot modify date once set");
		}

		if (! $datetime instanceof DateTimeImmutable) {
			$datetime = DateTimeImmutable::createFromMutable($datetime);
		}

		$this->datetime = $datetime;
	}

	/**
	 * Returns the associated date/time instance
	 *
	 * @return DateTimeImmutable
	 */
	public function getDateTime(): DateTimeImmutable
	{
		return $this->datetime;
	}

	/**
	 * Returns the associated date/time as a Unix timestamp.
	 *
	 * @return int
	 */
	public function getTimestamp(): int
	{
		return (int)$this->datetime->getTimestamp();
	}

	/**
	 * Returns the year as a 4-digit integer.
	 *
	 * @return int
	 */
	public function getYear(): int
	{
		return Date::year($this->datetime);
	}

	/**
	 * Returns the month as an integer.
	 *
	 * @return int 1 through 12
	 */
	public function getMonth(): int
	{
		return Date::month($this->datetime);
	}

	/**
	 * Returns the day as an integer.
	 *
	 * @return int 1 through 31
	 */
	public function getDay(): int
	{
		return Date::day($this->datetime);
	}

	/**
	 * Returns the day of the week as an integer.
	 * 
	 * 0 for Sunday, 1 for Monday, ... 6 for Saturday
	 *
	 * @return int
	 */
	public function getDayOfWeek(): int
	{
		return Date::dayOfWeek($this->datetime);
	}

	/**
	 * Returns the day of the year as an integer.
	 *
	 * @return int 0 through 365
	 */
	public function getDayOfYear(): int
	{
		return Date::dayOfYear($this->datetime);
	}

	/**
	 * Returns the number of days in the month.
	 *
	 * @return int 28 through 31
	 */
	public function getDaysInMonth(): int
	{
		return Date::daysInMonth($this->datetime);
	}

	/**
	 * Returns the full name of the day of the week (e.g. "Sunday").
	 *
	 * @return string
	 */
	public function getDayName(): string
	{
		return Date::dayName($this->datetime);
	}

	/**
	 * Returns the full name of the month (e.g. "January").
	 *
	 * @return string
	 */
	public function getMonthName(): string
	{
		return Date::monthName($this->datetime);
	}

	/**
	 * Returns the associated date/time timezone offset from GMT.
	 *
	 * @return int
	 */
	public function getTimezoneOffset(): int
	{
		return Date::timezoneOffset($this->datetime);
	}

	/**
	 * Whether the associated date is a weekday (Monday through Friday).
	 *
	 * @return bool
	 */
	public function isWeekday(): bool
	{
		return Date::isWeekday($this->datetime);
	}

	/**
	 * Whether the date is in daylight savings.
	 *
	 * @return bool
	 */
	public function isDaylightSavings(): bool
	{
		return Date::isDaylightSavings($this->datetime);
	}

}