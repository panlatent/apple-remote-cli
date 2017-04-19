<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Player\Ui;

use SplStack;

class UiDispatcher
{
    protected $uiStack;

    public function __construct(UiInterface $ui)
    {
        $this->uiStack = new SplStack();
        $this->uiStack->push($ui);
    }

    public function handle()
    {
//        if ( ! $this->uiStack->top()->isHidden()) {
            $result = $this->uiStack->top()->handle();
            if ($result === false) {
                $this->uiStack->pop();
            } elseif (is_object($result) && $result instanceof UiInterface) {
                $this->uiStack->push($result);
            }
//        }
    }
}