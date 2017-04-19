<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Player\Ui;

use Panlatent\AppleRemoteCli\Player\IntervalDispatcher;
use Symfony\Component\Console\Output\OutputInterface;

abstract class UiAbstract implements UiInterface
{
    /**
     * @var \Panlatent\AppleRemoteCli\Player\IntervalDispatcher
     */
    protected $dispatcher;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * @var bool
     */
    protected $handle;

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
    protected $rate;

    public function __construct(IntervalDispatcher $dispatcher, OutputInterface $output, PlayerUiModel $model,
        UiInterface $parent = null)
    {
        $this->dispatcher = $dispatcher;
        $this->output = $output;
        $this->model = $model;
        $this->parent = $parent;
    }

    abstract public function render();

    public function show()
    {
        $this->handle = true;
    }

    public function hidden()
    {
        $this->handle = false;
    }

    /**
     * @return \Panlatent\AppleRemoteCli\Player\Ui\UiInterface
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return int
     */
    public function getRate()
    {
        return $this->rate;
    }
}