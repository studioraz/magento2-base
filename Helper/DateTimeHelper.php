<?php
/*
 * Copyright © 2022 Studio Raz. All rights reserved.
 * See LICENCE file for license details.
 */
declare(strict_types=1);

namespace SR\Base\Helper;

class DateTimeHelper
{
    /**#@+ */
    public const TIMEZONE_ISRAEL = 'Asia/Jerusalem';
    public const TIMEZONE_UTC = 'UTC';

    public const TIMEZONE_DEFAULT = self::TIMEZONE_ISRAEL;
    /**#@- */

    /**
     * Returns Timezone Object for the Service
     *
     * @param string $zone [optional] TimeZone in String format
     *
     * @return \DateTimeZone
     */
    public static function getTimezone(string $zone = self::TIMEZONE_DEFAULT): \DateTimeZone
    {
        return new \DateTimeZone($zone);
    }

    /**
     * Returns formatted DateTime value
     *
     * @param string $datetime Expected value in the Format 'YYYY-MM-DD 00:00:00'
     * @param string $outputFormat [optional] the Date will look like '2020-01-12T23:59:21+00:00' ('c' = ISO 8601)
     * @param \DateTimeZone|null $timezone [optional] self::TIMEZONE_DEFAULT is used by default
     *
     * @return string
     */
    public static function getFormattedValue(string $datetime, string $outputFormat = 'c', ?\DateTimeZone $timezone = null): string
    {
        try {
            if ($timezone === null) {
                $timezone = self::getTimezone();
            }
            $date = (new \DateTime($datetime))->setTimezone($timezone);
        } catch (\Exception $e) {
            return $datetime;
        }

        return $date->format($outputFormat);
    }

    /**
     * Adds specified interval to Date Begin and Returns formatted date
     *
     * @param string $interval see https://www.php.net/manual/en/dateinterval.construct.php
     * @param string $dateBegin Start Date to add interval
     * @param string $format output date format
     * @param \DateTimeZone|null $timezone [optional] self::TIMEZONE_DEFAULT is used by default
     *
     * @return string
     */
    public static function addDateInterval(string $interval, string $dateBegin = 'now', string $format = 'c', ?\DateTimeZone $timezone = null): string
    {
        try {
            if ($timezone === null) {
                $timezone = self::getTimezone();
            }
            $date = (new \DateTime($dateBegin))->setTimezone($timezone);
            $date->add(new \DateInterval($interval));
        } catch (\Exception $e) {
            // FIXME: WORKAROUND to proceed with correct intervals and return valid value
            $intervalMappings = [
                'P24H' => 60 * 60 * 24,// NOTE: +24H
            ];

            return date($format, time() + ($intervalMappings[$interval] ?? 0));
        }

        return $date->format($format);
    }

    /**
     * Subtracts specified interval to Date Begin and Returns formatted date
     *
     * @param string $interval see https://www.php.net/manual/en/dateinterval.construct.php (Subtrahend)
     * @param string $dateBegin Start Date to subtract interval (Minuend)
     * @param string $format output date format
     * @param \DateTimeZone|null $timezone [optional] self::TIMEZONE_DEFAULT is used by default
     *
     * @return string
     */
    public static function subtractDateInterval(string $interval, string $dateBegin = 'now', string $format = 'c', ?\DateTimeZone $timezone = null): string
    {
        try {
            if ($timezone === null) {
                $timezone = self::getTimezone();
            }
            $date = (new \DateTime($dateBegin))->setTimezone($timezone);
            $date->sub(new \DateInterval($interval));
        } catch (\Exception $e) {
            // FIXME: WORKAROUND to proceed with correct intervals and return valid value
            $intervalMappings = [
                'P24H' => 60 * 60 * 24,// NOTE: +24H
            ];

            return date($format, time() - ($intervalMappings[$interval] ?? 0));
        }

        return $date->format($format);
    }

    /**
     * Returns Timestamp of give DateTime
     * NOTE: the result is based on self::getTimezone
     *
     * @param string $datetime Expected value in the Format 'YYYY-MM-DD 00:00:00'
     * @param \DateTimeZone|null $timezone [optional] self::TIMEZONE_DEFAULT is used by default
     *
     * @return int {timestamp}
     */
    public static function getTimestamp(string $datetime, ?\DateTimeZone $timezone = null): int
    {
        try {
            if ($timezone === null) {
                $timezone = self::getTimezone();
            }
            return (new \DateTime($datetime))->setTimezone($timezone)->getTimestamp();
        } catch (\Exception $e) {
            return 0;
        }
    }
}
