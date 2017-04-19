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

    public function __construct(Dispatcher $dispatcher, $delay)
    {
        $this->dispatcher = $dispatcher;
        $this->delay = $delay;
    }

    final public function run()
    {
        $this->interval();
        $this->dispatcher->addTimer($this);
    }

    abstract public function interval();
}