<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Player;

use GuzzleHttp\Exception\ClientException;
use Panlatent\AppleRemoteCli\PlayControl;
use Panlatent\AppleRemoteCli\PlayStatus;
use Panlatent\AppleRemoteCli\Ui\Model;
use Panlatent\AppleRemoteCli\Ui\ViewModel;

abstract class PlayerModel extends Model
{
    protected $dispatcher;

    protected $control;

    public function __construct(ViewModel $viewModel, PlayControl $control)
    {
        parent::__construct($viewModel);

        $this->control = $control;
    }

    public function handle()
    {
        try {
            if ($this->viewModel->isLock()) {
                $this->update();
                $this->viewModel->unlock();
            }
            $playStatue = $this->control->getPlayStatus();
        } catch (ClientException $e) {
            die(0);
        }

        $this->viewModel->playState = $playStatue->playStatus;
        $this->viewModel->shuffle = $playStatue->shuffle;
        $this->viewModel->repeat = $playStatue->repeat;
        $this->viewModel->songTime = $playStatue->songTime;
        $this->viewModel->songCurrentTime = $playStatue->songTime - $playStatue->songTimeRemaining;
        $this->viewModel->songRemainingTime = $playStatue->songTimeRemaining;
        $this->viewModel->songName = $playStatue->songName;
        $this->viewModel->songArtist = $playStatue->songArtist;
        $this->viewModel->songAlbum = $playStatue->songAlbum;
    }

    protected function update()
    {
        if (isset($this->viewModel->signal) && $this->viewModel->signal !== null) {
            if ($this->viewModel->signal == PlayerViewModel::SIGNAL_LAST_SONG) {
                $this->control->last();
            } else if ($this->viewModel->signal == PlayerViewModel::SIGNAL_NEXT_SONG)  {
                $this->control->next();
            }
            $this->viewModel->signal = null;
            return;
        }

        $changes = $this->viewModel->pop();
        if ( ! empty($changes)) {
            foreach ($changes as $property) {
                switch ($property) {
                    case 'playState':
                        if ($this->viewModel->playState == PlayStatus::PLAY) {
                            $this->control->play();
                        } elseif ($this->viewModel->playState == PlayStatus::PAUSE) {
                            $this->control->pause();
                        }
                        break;
                    case 'shuffle':
                        $this->control->shuffle($this->viewModel->shuffle);
                        break;
                    case 'repeat':
                        $this->control->repeat($this->viewModel->repeat);
                        break;
                    default:
                        break;
                }
            }
        }
    }
}