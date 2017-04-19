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
        $drag = 0;
        $this->done = false;
        while ( ! $this->done && ! $this->timers->isEmpty()) {
            $timer = $this->timers->top();
            $delay = $timer->getDelay();
            if ($delay >= $drag - 5) { // 5ms
                $sec = (int)($delay / 1000);
                $micro = $delay % 1000;

                $before = microtime(true);
                if (time_nanosleep($sec, $micro * 1000)) {

                }
                $sleep = (microtime(true) - $before) * 1000;
                if ($sleep + $drag >= $delay) {
                    $timer->run();
                    $this->timers->extract();
                }
            } else {
                $before = microtime(true);
                $timer->run();
                $this->timers->extract();
            }

            $drag = (int)(microtime(true) - $before) * 1000;
        }
    }
}