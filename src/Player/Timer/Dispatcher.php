<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Player\Timer;

class Dispatcher
{
    protected $done = true;

    protected $amend = 0;

    protected $timers;

    public function __construct()
    {
        $this->timers = new TimeMinHeap();
    }

    public function addTimer(TimerInterface $timer)
    {
        $this->timers->insert($timer);
    }

    public function handle()
    {
        $this->done = false;
        while ( ! $this->done && ! $this->timers->isEmpty()) {
            $timer = $this->timers->top();

            $beforeDelay = (int)(microtime(true) * 1000);
            $elapsed = $beforeDelay - $timer->getBorn();

            $delay = $timer->getDelay();
            if ($delay >= $elapsed) { // 5ms - 5
                $delay -= $elapsed;

                $sec = (int)($delay / 1000);
                $micro = $delay % 1000;

                $beforeSleep = microtime(true);
                if (time_nanosleep($sec, $micro * 1000)) {

                }
                $sleep = (microtime(true) - $beforeSleep) * 1000;

                if ($sleep >= $delay) {
                    $timer->run();
                    $this->timers->extract();
                }
            } else {
                $timer->run();
                $this->timers->extract();
            }
        }
    }

    public function clear()
    {
        $this->done = true;
    }
}