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

    protected $io;

    public function __construct(Dispatcher $dispatcher, UiInterface $ui)
    {
        $this->uiStack = new SplStack();
        $this->uiStack->push($ui);
        $this->io = new PlayerIo($this, $ui->getModel());

        parent::__construct($dispatcher, $ui->getRate());
    }

    public function interval()
    {
        if ($this->uiStack->top()->getModel()->isLock()) {
            echo '.';
            return;
        }

        $result = $this->uiStack->top()->handle();
        if ($result === false) {
            $this->uiStack->pop();
            if ($this->uiStack->isEmpty()) {
                $this->clear();
            } else {
                $this->delay = $this->uiStack->top()->getRate();
                $this->uiStack->top()->show();
            }
        } elseif (is_object($result) && $result instanceof UiInterface) {
            $this->uiStack->top()->hidden();
            $this->uiStack->push($result);
            $this->delay = $this->uiStack->top()->getRate();
        }

        $this->io->run();
    }

    public function clear()
    {
        parent::clear();
        $this->dispatcher->clear();
    }

    public function wait()
    {
        $this->uiStack->top()->getModel()->lock(); // Must before render
        $this->uiStack->top()->render();
    }
}