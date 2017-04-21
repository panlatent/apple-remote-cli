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

class PlayerModel extends \Panlatent\AppleRemoteCli\Player\PlayerModel
{
    public function __construct(PlayerViewModel $viewModel, PlayControl $control)
    {
        parent::__construct($viewModel, $control);

        $this->control = $control;
//        $this->ui = new PlayerModel($this->dispatcher,
//            new PlayerView($this->dispatcher, $this->output, new PlayerUiModel()));


    }




}