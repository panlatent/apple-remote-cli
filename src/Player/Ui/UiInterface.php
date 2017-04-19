<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Player\Ui;

use Panlatent\AppleRemoteCli\Player\IntervalTaskInterface;

interface UiInterface extends IntervalTaskInterface
{
    public function show();

    public function hidden();

    public function render();

    public function getParent();

    public function getRate();
}