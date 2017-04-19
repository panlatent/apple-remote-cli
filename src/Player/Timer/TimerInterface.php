<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Player\Timer;

interface TimerInterface
{
    /**
     * @return int microsecond
     */
    public function getDelay();

    /**
     * @return int
     */
    public function getBorn();

    /**
     * @param int $time
     */
    public function setBorn($time);

    /**
     * @return void
     */
    public function run();
}