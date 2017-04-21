<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Player;

use Panlatent\AppleRemoteCli\PlayControl;
use Panlatent\AppleRemoteCli\Player\Cui\Application as CuiApplication;
use Panlatent\AppleRemoteCli\Player\Gui\Application as GuiApplication;
use Panlatent\Timer\Dispatcher;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Player
{
    protected $dispatcher;

    protected $input;

    protected $output;

    protected $control;

    protected $model;

    protected $gui;

    public function __construct(InputInterface $input, OutputInterface $output, PlayControl $control)
    {
        $this->dispatcher = new Dispatcher();
        $this->input = $input;
        $this->output = $output;
        $this->control = $control;
    }

    public function withGui()
    {
        $this->gui = true;
    }

    public function run()
    {
        if ( ! $this->gui) {
            new CuiApplication($this->output, $this->control);
        } else {
            new GuiApplication($this->control);
        }
    }


}