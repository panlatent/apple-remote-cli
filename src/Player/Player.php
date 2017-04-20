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
use Panlatent\AppleRemoteCli\Player\Timer\Dispatcher;
use Panlatent\AppleRemoteCli\Player\Timer\Interval;
use Panlatent\AppleRemoteCli\Player\Ui\PlayerUi;
use Panlatent\AppleRemoteCli\Player\Ui\PlayerUiModel;
use Panlatent\AppleRemoteCli\Player\Ui\UiDispatcher;
use Panlatent\AppleRemoteCli\PlayStatus;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Player
{
    protected $dispatcher;

    protected $input;

    protected $output;

    protected $control;

    protected $ui;

    protected $model;

    public function __construct(InputInterface $input, OutputInterface $output, PlayControl $control)
    {
        $this->dispatcher = new Dispatcher();
        $this->input = $input;
        $this->output = $output;
        $this->control = $control;
        $this->model = new PlayerUiModel();

        $this->ui = new UiDispatcher($this->dispatcher, new PlayerUi($this->dispatcher, $output, $this->model));
        $this->dispatcher->addTimer($this->ui);
        $this->dispatcher->addTimer(new Interval($this->dispatcher, 1000 * 4, [$this, 'handle']));
    }

    public function run()
    {
        $this->handle();
        $this->dispatcher->handle();
    }

    public function handle()
    {
        try {
            if ($this->model->isLock()) {
                $this->update();
                $this->model->unlock();
            }

            $playStatue = $this->control->getPlayStatus();
        } catch (ClientException $e) {
            die(0);
        }
        $this->model->playState = $playStatue->playStatus;
        $this->model->shuffle = $playStatue->shuffle;
        $this->model->repeat = $playStatue->repeat;
        $this->model->songTime = $playStatue->songTime;
        $this->model->songCurrentTime = $playStatue->songTime - $playStatue->songTimeRemaining;
        $this->model->songRemainingTime = $playStatue->songTimeRemaining;
        $this->model->songName = $playStatue->songName;
        $this->model->songArtist = $playStatue->songArtist;
        $this->model->songAlbum = $playStatue->songAlbum;
    }

    protected function update()
    {
        if (isset($this->model->signal) && $this->model->signal !== null) {
            if ($this->model->signal == PlayerUiModel::SIGNAL_LAST_SONG) {
                $this->control->last();
            } else if ($this->model->signal == PlayerUiModel::SIGNAL_NEXT_SONG)  {
                $this->control->next();
            }
            $this->model->signal = null;
            return;
        }

        $changes = $this->model->pop();
        if ( ! empty($changes)) {
            foreach ($changes as $property) {
                switch ($property) {
                    case 'playState':
                        if ($this->model->playState == PlayStatus::PLAY) {
                            $this->control->play();
                        } elseif ($this->model->playState == PlayStatus::PAUSE) {
                            $this->control->pause();
                        }
                        break;
                    case 'shuffle':
                        $this->control->shuffle($this->model->shuffle);
                        break;
                    case 'repeat':
                        $this->control->repeat($this->model->repeat);
                        break;
                    default:
                        break;
                }
            }
        }

    }
}