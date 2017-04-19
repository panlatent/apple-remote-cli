<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Player\Timer;

use SplMinHeap;

/**
 * Class MinHeap
 *
 * @package Panlatent\AppleRemoteCli\Player\Timer
 * @method TimerInterface top();
 * @method TimerInterface extract();
 */
class TimeMinHeap extends SplMinHeap
{
    public function insert($timer)
    {
        $timer->setBorn((int)(microtime(true) * 1000));
        parent::insert($timer);
    }

    /**
     * @param \Panlatent\AppleRemoteCli\Player\Timer\TimerInterface $timer1
     * @param \Panlatent\AppleRemoteCli\Player\Timer\TimerInterface $timer2
     * @return int
     */
    protected function compare($timer1, $timer2)
    {
        return ($timer2->getBorn() + $timer2->getDelay()) - ($timer1->getBorn() + $timer1->getDelay());
    }
}