<?php
/*
 * Copyright © 2021 Studio Raz. All rights reserved.
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
     * @return string
     */
    public static function getFormattedValue(string $datetime, string $outputFormat = 'c'): string
    {
        try {
            $timestamp = time();
            $date = new \DateTime($datetime, self::getTimezone());
            $date->setTimestamp($timestamp);
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
     *
     * @return string
     */
    public static function addDateInterval(string $interval, string $dateBegin = 'now', string $format = 'c'): string
    {
        try {
            $date = new \DateTime($dateBegin, self::getTimezone());
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
}
