<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Player\Gui;

use Gui\Application as Gui;
use Panlatent\AppleRemoteCli\PlayControl;
use Panlatent\AppleRemoteCli\Player\PlayerViewModel;

class Application
{
    protected $control;

    protected $window;

    public function __construct(PlayControl $control)
    {
        $this->viewModel = new PlayerViewModel();
        $this->model = new PlayerModel($this->viewModel, $control);
        $this->window = new PlayerWindow($this->viewModel);

        $gui = new Gui();
        $gui->on('start', function() use ($gui) {
            $this->window->init($gui);
            $gui->getLoop()->addPeriodicTimer(1, function() {
                $this->window->handle();
            });
            $gui->getLoop()->addPeriodicTimer(4, function() {
                $this->model->handle();
            });
        });

        $gui->run();
    }
}