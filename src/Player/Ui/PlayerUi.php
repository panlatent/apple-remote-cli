<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Player\Ui;

use Panlatent\AppleRemoteCli\Commands\Exception;
use Panlatent\AppleRemoteCli\Player\IntervalTaskInterface;
use Panlatent\AppleRemoteCli\PlayStatus;
use Symfony\Component\Console\Helper\ProgressBar;

class PlayerUi extends UiAbstract implements IntervalTaskInterface
{
    /**
     * @var bool
     */
    protected $handle = true;

    protected $rate = 1000000;

    /**
     * @var \Symfony\Component\Console\Helper\ProgressBar
     */
    protected $progress;

    /**
     * @var int
     */
    protected $currentTime;

    public function isHandle()
    {
        return $this->handle;
    }

    public function handle()
    {
        if ( ! isset($this->model->playState) || $this->model->playState == PlayStatus::STOP) {
            if ($this->progress) {
                $this->freeUi();
            }
            $this->handle = false;
            return new CoverUi($this->dispatcher, $this->output, $this->model, $this);
        }

        $this->render();
        return true;
    }

    public function render()
    {
        if ( ! $this->progress) {
            $this->refreshUi();
        } else {
            $this->update();
        }
    }

    protected function refreshUi()
    {
        $this->progress = $this->makeProgressBar((int)($this->model->songTime/1000));
        $this->progress->setProgress((int)($this->model->songCurrentTime/1000));
        $this->update();
        $this->progress->start();
    }

    protected function freeUi()
    {
        $this->progress->finish();
        $this->progress->clear();
        $this->progress = null;
    }

    protected function update()
    {
        $changes = $this->model->pop();
        foreach ($changes as $property) {
            $this->progress->setMessage($this->getText($property, $this->model->$property), $property);
        }
        if (in_array('songCurrentTime', $changes)) {
            $this->currentTime = $this->model->songCurrentTime;
            $this->progress->setProgress((int)($this->currentTime/1000));
        } elseif ( ! in_array('songCurrentTime', $changes) && $this->model->playState == PlayStatus::PLAY) {
            $this->currentTime += 1000;
            $this->progress->advance();
            $this->updateTime();
        }
    }

    protected function updateTime()
    {
        $this->progress->setMessage($this->getTimeText($this->currentTime), 'songCurrentTime');
        $this->progress->setMessage($this->getTimeText($this->model->songTime - $this->currentTime), 'songRemainingTime');
    }

    protected function makeProgressBar($max, $params = [])
    {
        $progress = new ProgressBar($this->output, $max);
        $progress->setBarCharacter('<fg=blue>⁍</>');
        $progress->setEmptyBarCharacter('<fg=white>⁍</>');
        $progress->setProgressCharacter('<fg=green>⁍</>');
        $progress->setBarWidth(50);
        $progress->setFormat($this->processFormat(
            '%songName%' . "\r\n" .
            '%songArtist% - %songAlbum%' . "\r\n" .
            '%playState% %songCurrentTime% <fg=white>⁌</>%bar%<fg=white>⁍</> %percent:3s%% -%songRemainingTime% / %songTime%' .
            ' %shuffle% %repeat%'
        , $params));

        return $progress;
    }

    protected function processFormat($format, $params = [])
    {
        return preg_replace_callback('#%(\w+?)%#', function($match) use($params) {
            if (isset($params[$match[1]])) {
                return $params[$match[1]];
            }
            return '%' . $match[1] . '%';
        }, $format);
    }

    protected function getText($key, $value)
    {
        switch ($key) {
            case 'playState':
                return $this->getPlayStateText($value);
            case 'shuffle':
                return $this->getShuffleText($value);
            case 'repeat':
                return $this->getRepeatText($value);
            case 'songTime':
            case 'songCurrentTime':
            case 'songRemainingTime':
                return $this->getTimeText($value);
            default:
                return $value;
        }
    }

    protected function getTimeText($time)
    {
        return preg_replace('#^0(?=\d+)#', '', date('i:s', (int)($time/1000)));
    }

    protected function getPlayStateText($playState)
    {
        switch ($playState) {
            case PlayStatus::PLAY:
                return '<fg=blue>Play</>';
            case PlayStatus::PAUSE:
                return '<fg=yellow>Pause</>';
            case PlayStatus::STOP:
                return '<fg=red>Stop</>';
            default:
                throw new Exception('Unknown play status');
        }
    }

    protected function getShuffleText($shuffle)
    {
        if ($shuffle == PlayStatus::SHUFFLE) {
            return '<fg=blue>Shuffle</>';
        }

        return '----';
    }

    protected function getRepeatText($repeat)
    {
        switch ($repeat) {
            case PlayStatus::NOT_LOOP:
                return '----';
            case PlayStatus::SINGLE_LOOP:
                return '<fg=yellow>Single</>';
            case PlayStatus::LOOP:
                return '<fg=blue>Loop</>';
            default:
                throw new Exception('Unknown loop type');
        }
    }
}