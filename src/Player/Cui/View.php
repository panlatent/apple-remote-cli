<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Player\Cui;

abstract class View extends \Panlatent\AppleRemoteCli\Ui\View
{
    /**
     * @var int
     */
    protected $rate = 100;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * @var \Panlatent\AppleRemoteCli\Player\PlayerViewModel
     */
    protected $viewModel;

    /**
     * @return int
     */
    public function getRate()
    {
        return $this->rate;
    }

    public function show()
    {
        $this->hidden = false;
       // $this->model->reentry();
    }
}