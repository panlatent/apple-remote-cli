<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Player\Ui;

use Panlatent\AppleRemoteCli\Player\Timer\Dispatcher;
use Panlatent\AppleRemoteCli\Player\Timer\IntervalTimer;
use SplStack;

class UiDispatcher extends IntervalTimer
{
    protected $uiStack;

    public function __construct(Dispatcher $dispatcher, UiInterface $ui)
    {
        $this->uiStack = new SplStack();
        $this->uiStack->push($ui);

        parent::__construct($dispatcher, $ui->getRate());
    }

    public function interval()
    {
//        if ( ! $this->uiStack->top()->isHidden()) {
            $result = $this->uiStack->top()->handle();
            if ($result === false) {
                $this->uiStack->pop();
            } elseif (is_object($result) && $result instanceof UiInterface) {
                $this->uiStack->push($result);
                $this->delay = $result->getRate();
            }
//        }
    }
}