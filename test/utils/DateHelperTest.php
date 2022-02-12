<?php
/**
 * @file   : DateHelperTest.php
 * @time   : 10:45
 * @date   : 2021/9/10
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\utils;

use DateTime;
use Exception;
use Hiland\Utils\Data\DateHelper;
use PHPUnit\Framework\TestCase;

class DateHelperTest extends TestCase
{
    public function testGetInterval()
    {
        $startValue = new DateTime("2021-09-10");
        $endValue = new DateTime("2021-10-10");

        $actual = null;
        try {
            $actual = DateHelper::getInterval($startValue, $endValue);
        } catch (Exception $e) {
        }
        // dump($actual);
        self::assertEquals(0, $actual->invert);
        /**
         * ════════════════════════
         */
        $startValue = new DateTime("2021-09-10");
        $endValue = new DateTime("2021-8-10");

        $actual = null;
        try {
            $actual = DateHelper::getInterval($startValue, $endValue);
        } catch (Exception $e) {
        }
        self::assertEquals(1, $actual->invert);
    }

    public function testGetDateTime()
    {

        $timespan = 2145888000;
        $actual = "";
        try {
            $actual = DateHelper::getDateTime($timespan);
        } catch (Exception $e) {
        }
        $expected = new DateTime("2038-1-1 0:0:0");
        self::assertEquals($expected, $actual);

        $timespan = 2145888002;
        try {
            $actual = DateHelper::getDateTime($timespan);
        } catch (Exception $e) {
        }
        $expected = new DateTime("2038-1-1 0:0:2");
        self::assertEquals($expected, $actual);
    }


    public function testParseDateTimeSafely()
    {
        $dateString = "";
        $actual = DateHelper::parseDateTimeSafely($dateString);
        self::assertEquals(false, $actual);

        $dateString = 2145888000;
        $actual = DateHelper::parseDateTimeSafely($dateString);
        $expected = new DateTime("2038-1-1 0:0:0");
        self::assertEquals($expected, $actual);
    }

    // public function testGetTimeZone(){
    //     echo ini_get("date.timezone");
    // }

    // public function testFormat()
    // {
    //
    // }
    // public function testAddInterval()
    // {
    //
    // }
    // public function testGetTimestamp()
    // {
    //
    // }
}
