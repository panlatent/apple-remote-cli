<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Player\Timer;

class Interval extends IntervalTimer
{
    protected $callback;

    public function __construct(Dispatcher $dispatcher, $delay, $callback)
    {
        parent::__construct($dispatcher, $delay);

        $this->callback = $callback;
    }

    public function interval()
    {
        call_user_func($this->callback);
    }
}