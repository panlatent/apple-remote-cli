<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Player\Cui;

use Panlatent\AppleRemoteCli\Player\PlayerViewModel;
use Panlatent\AppleRemoteCli\PlayStatus;

class PlayerIo
{
    protected $application;

    protected $model;

    public function __construct(Application $application, PlayerViewModel $model)
    {
        $this->application = $application;
        $this->model = $model;
        shell_exec('stty -echo time 0 -icanon');
        stream_set_blocking(STDIN, false);
    }

    public function run()
    {
        if (($c = fgetc(STDIN))) {
            switch ($c) {
                case 'q': // quit
                    $this->application->clear();
                    break;
                case 'k': // last song
                    $this->model->signal = PlayerViewModel::SIGNAL_LAST_SONG;
                    break;
                case 'j': // next song
                    $this->model->signal = PlayerViewModel::SIGNAL_NEXT_SONG;
                    break;
                case 'h': // back
                    break;
                case 'l': // forward
                    break;
                case 'p': // play or pause
                    if ($this->model->playState === PlayStatus::PLAY) {
                        $this->model->playState = PlayStatus::PAUSE;
                    } else {
                        $this->model->playState = PlayStatus::PLAY;
                    }
                    break;
                case 's': // shuffle
                    if ($this->model->shuffle === PlayStatus::SHUFFLE) {
                        $this->model->shuffle = PlayStatus::NOT_SHUFFLE;
                    } else {
                        $this->model->shuffle = PlayStatus::SHUFFLE;
                    }
                    break;
                case 'r': // repeat
                    if ($this->model->repeat == PlayStatus::NOT_LOOP) {
                        $this->model->repeat = PlayStatus::LOOP;
                    } elseif ($this->model->repeat == PlayStatus::LOOP) {
                        $this->model->repeat = PlayStatus::SINGLE_LOOP;
                    } else {
                        $this->model->repeat = PlayStatus::NOT_LOOP;
                    }
                    break;
                case '^': // move start time
                    $this->model->signal = -2;
                    break;
                case '$': // move end time?
                    break;
                default:
                    return;
            }
            $this->application->wait();
        }
    }
}