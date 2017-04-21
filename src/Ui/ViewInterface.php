<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Ui;

interface ViewInterface
{
    public function isHidden();

    public function getParent();

    public function getViewModel();

    public function show();

    public function hidden();

    public function handle();

    public function render();
}