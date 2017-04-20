<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Player\Timer;

abstract class IntervalTimer extends TimerAbstract
{
    protected $dispatcher;

    protected $running;

    public function __construct(Dispatcher $dispatcher, $delay)
    {
        $this->dispatcher = $dispatcher;
        $this->delay = $delay;
        $this->running = true;
    }

    final public function run()
    {
        $this->interval();
        if ($this->running) {
            $this->dispatcher->addTimer($this);
        }
    }

    abstract public function interval();

    public function clear()
    {
        $this->running = false;
    }
}