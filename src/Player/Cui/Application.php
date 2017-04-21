<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Player\Cui;

use Panlatent\AppleRemoteCli\PlayControl;
use Panlatent\AppleRemoteCli\Player\PlayerViewModel;
use Panlatent\AppleRemoteCli\Ui\ViewInterface;
use Panlatent\Timer\Dispatcher;
use Panlatent\Timer\Interval;
use Panlatent\Timer\IntervalTimer;
use SplStack;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends IntervalTimer
{
    protected $viewStack;

    protected $model;

    protected $viewModel;

    protected $io;

    public function __construct(OutputInterface $output, PlayControl $control)
    {
        parent::__construct(new Dispatcher(), 1000);

        $this->viewStack = new SplStack();
        $this->viewModel = new PlayerViewModel();
        $this->model = new PlayerModel($this->viewModel, $control);
        $view = new PlayerView($output, $this->viewModel);
        $this->viewStack->push($view);

        $this->io = new PlayerIo($this, $this->viewModel);
        $this->dispatcher->addTimer($this);
        $this->dispatcher->addTimer(new Interval($this->dispatcher, 1000 * 4, [$this->model, 'handle']));

        $this->model->handle();
        $this->dispatcher->dispatch();
    }

    public function interval()
    {
        if ($this->viewModel->isLock()) {
            echo '.';
            return;
        }

        $result = $this->viewStack->top()->handle();
        if ($result === false) {
            $this->viewStack->pop();
            if ($this->viewStack->isEmpty()) {
                $this->clear();
            } else {
                $this->delay = $this->viewStack->top()->getRate();
                $this->viewStack->top()->show();
            }
        } elseif (is_object($result) && $result instanceof ViewInterface) {
            $this->viewStack->top()->hidden();
            $this->viewStack->push($result);
            $this->delay = $this->viewStack->top()->getRate();
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
        $this->viewModel->lock(); // Must before render
        $this->viewStack->top()->render();
    }
}