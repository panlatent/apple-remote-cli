<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Ui;

class Model
{
    /**
     * @var \Panlatent\AppleRemoteCli\Ui\ViewModel
     */
    protected $viewModel;

    public function __construct(ViewModel $viewModel)
    {
        $this->viewModel = $viewModel;
    }

    public function handle()
    {

    }
}