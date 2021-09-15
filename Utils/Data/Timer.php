<?php
/**
 * @file   : Timer.php
 * @time   : 15:08
 * @date   : 2021/9/15
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */


namespace Hiland\Utils\Data;

/**
 * 计时器
 */
class Timer
{
    var $begin = 0;
    var $end = 0;
    var $elapsed = 0;

    # Constructor
    function Timer($start = true)
    {
        if ($start) {
            $this->start();
        }
    }

    # Start counting time
    function start()
    {
        $this->begin = $this->_getTime();
    }

    # Stop counting time
    function stop()
    {
        $this->end = $this->_getTime();
        $this->elapsed = $this->_compute();
    }

    # Get Elapsed Time
    function elapsed()
    {
        if (!$this->elapsed) {
            $this->stop();
        }

        return $this->elapsed;
    }

    # Resets Timer so it can be used again
    function reset()
    {
        $this->begin = 0;
        $this->end = 0;
        $this->elapsed = 0;
    }

    #### PRIVATE METHODS ####

    # Get Current Time
    private function _getTime()
    {
        $mtime = microtime();
        $mtime = explode(" ", $mtime);
        return $mtime[1] + $mtime[0];
    }

    # Compute elapsed time
    private function _compute()
    {
        return $this->end - $this->begin;
    }
}