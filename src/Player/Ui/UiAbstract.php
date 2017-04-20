<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Player\Ui;

use Panlatent\Timer\Dispatcher;
use Symfony\Component\Console\Output\OutputInterface;

abstract class UiAbstract implements UiInterface
{
    /**
     * @var \Panlatent\Timer\Dispatcher
     */
    protected $dispatcher;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * @var bool
     */
    protected $hidden;

    /**
     * @var \Panlatent\AppleRemoteCli\Player\Ui\PlayerUiModel
     */
    protected $model;

    /**
     * @var \Panlatent\AppleRemoteCli\Player\Ui\UiInterface
     */
    protected $parent;

    /**
     * @var int
     */
    protected $rate = 1000;

    public function __construct(
        Dispatcher $dispatcher, OutputInterface $output, PlayerUiModel $model,
        UiInterface $parent = null)
    {
        $this->dispatcher = $dispatcher;
        $this->output = $output;
        $this->model = $model;
        $this->parent = $parent;
    }

    abstract public function render();

    /**
     * @return \Panlatent\AppleRemoteCli\Player\Ui\UiInterface
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return \Panlatent\AppleRemoteCli\Player\Ui\PlayerUiModel
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return int
     */
    public function getRate()
    {
        return $this->rate;
    }

    public function isHidden()
    {
        return $this->hidden;
    }

    public function show()
    {
        $this->hidden = false;
        $this->model->reentry();
    }

    public function hidden()
    {
        $this->hidden = true;
    }
}