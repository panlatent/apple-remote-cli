<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Ui;

abstract class View implements ViewInterface
{
    /**
     * @var bool
     */
    protected $hidden;

    /**
     * @var \Panlatent\AppleRemoteCli\Ui\ViewModel
     */
    protected $viewModel;

    /**
     * @var \Panlatent\AppleRemoteCli\Ui\ViewInterface
     */
    protected $parent;

    public function __construct(ViewModel $model, ViewInterface $parent = null)
    {
        $this->viewModel = $model;
        $this->parent = $parent;
    }

    abstract public function render();

    /**
     * @return \Panlatent\AppleRemoteCli\Ui\ViewInterface
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return \Panlatent\AppleRemoteCli\Ui\ViewModel
     */
    public function getViewModel()
    {
        return $this->viewModel;
    }

    public function isHidden()
    {
        return $this->hidden;
    }

    public function show()
    {
        $this->hidden = false;
    }

    public function hidden()
    {
        $this->hidden = true;
    }
}