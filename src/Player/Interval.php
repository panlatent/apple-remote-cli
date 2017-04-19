<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Player;

class Interval
{
    public $interval;

    public $fixed;

    public $real = 0;

    public function __construct($interval)
    {
        $this->interval = $interval;
        $this->fixed = $interval;
    }
}