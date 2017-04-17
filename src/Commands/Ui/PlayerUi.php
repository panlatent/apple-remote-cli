<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Commands\Ui;

use GuzzleHttp\Exception\ClientException;
use Panlatent\AppleRemoteCli\Commands\Exception;
use Panlatent\AppleRemoteCli\PlayControl;
use Panlatent\AppleRemoteCli\PlayStatus;
use SplStack;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PlayerUi
{
    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * @var \Symfony\Component\Console\Helper\ProgressBar
     */
    protected $progress;

    /**
     * @var \Panlatent\AppleRemoteCli\PlayControl
     */
    protected $playControl;

    /**
     * @var \Panlatent\AppleRemoteCli\PlayStatus
     */
    protected $playStatue;

    /**
     * @var int
     */
    protected $time;

    /**
     * @var int
     */
    protected $currentTime;

    /**
     * @var int
     */
    protected $remainingTime;

    /**
     * @var bool
     */
    protected $updated = false;

    /**
     * @var \SplStack
     */
    protected $uiChangeStack;

    public function __construct(InputInterface $input, OutputInterface $output, PlayControl $playControl)
    {
        $this->input = $input;
        $this->output = $output;
        $this->playControl = $playControl;
        $this->uiChangeStack = new SplStack();
    }

    public function cover()
    {
        $coverBar = $this->makeCoverProgressBar();
        $coverBar->start();
        for ($i = 0; $i < 10; ++$i) {
            $coverBar->advance($i * 10);
            usleep(100000);
        }
        $coverBar->clear();
    }

    public function show()
    {
        $requestLoop = 0;
        do {
            $markTime = microtime(true);
            if ($requestLoop == 0) {
                try {
                    $this->getPlayStatus();
                    $this->checkPlayStatusExpire();
                } catch (ClientException $e) {
                    break;
                }
                $requestLoop = 4;
            }
            if ($this->playStatue->playStatus != PlayStatus::STOP) {
                if ($this->updated) {
                    $this->freeUi();
                    $this->refreshUi();
                    $this->updated = false;
                } else {
                    $this->updatePlayIconArea();
                    $this->updateTimeArea();
                }

                if ($requestLoop == 4) {
                    $this->progress->setProgress($this->currentTime);
                } else {
                    if ($this->playStatue->playStatus == PlayStatus::PLAY) {
                        $this->progress->advance();
                        $this->currentTime += 1;
                    }
                }
                if (microtime(true) < $markTime + 1) {
                    time_sleep_until($markTime + 1);
                }
            } else {
                if ($this->updated) {
                    $this->freeUi();
                    $this->updated = false;
                }
                $this->cover();
            }
            $requestLoop -= 1;
        } while (true);

        $this->freeUi();
    }

    protected function refreshUi()
    {
        $this->progress = $this->makeProgressBar($this->time, [
            'songName'   => $this->playStatue->songName,
            'songArtist' => $this->playStatue->songArtist,
            'songAlbum'  => $this->playStatue->songAlbum,
            'songTime'   => $this->getTimeTag($this->time),
        ]);
        $this->updatePlayIconArea();
        $this->updateTimeArea();
        $this->progress->start();
    }

    protected function freeUi()
    {
        if ($this->progress) {
            $this->progress->finish();
            $this->progress->clear();
            $this->progress = null;
        }
    }

    protected function updatePlayIconArea()
    {
        $this->progress->setMessage($this->getPlayIcon(), 'playIcon');
        $this->progress->setMessage($this->getShuffleIcon(), 'shuffleIcon');
        $this->progress->setMessage($this->getRepeatIcon(), 'repeatIcon');
    }

    protected function updateTimeArea()
    {
        $this->progress->setMessage($this->getTimeTag($this->currentTime), 'currentTime');
        $this->progress->setMessage($this->getTimeTag($this->progress->getMaxSteps() - $this->progress->getProgress()),
            'remainingTime');
    }

    protected function getPlayStatus()
    {
        $this->playStatue = $this->playControl->getPlayStatus();
        $this->time = (int)($this->playStatue->songTime / 1000);
        $this->remainingTime = (int)$this->playStatue->songTimeRemaining / 1000;
        $this->currentTime = $this->time - $this->remainingTime;

        $this->uiChangeStack->push(md5($this->playStatue->songName . $this->playStatue->songArtist . $this->time));
    }

    protected function checkPlayStatusExpire()
    {
        if (count($this->uiChangeStack) == 1) {
            $this->updated = true;
            return;
        }

        $top = $this->uiChangeStack->pop();
        if ($top !== $this->uiChangeStack->pop()) {
            $this->updated = true;
        }
        $this->uiChangeStack->push($top);
    }

    protected function makeProgressBar($max, $params = [])
    {
        $progress = new ProgressBar($this->output, $max);
        $progress->setBarCharacter('<fg=blue>⁍</>');
        $progress->setEmptyBarCharacter('<fg=white>⁍</>');
        $progress->setProgressCharacter('<fg=green>⁍</>');
        $progress->setBarWidth(50);
        $progress->setFormat($this->processFormat(
            '%songName%' . "\n" .
            '%songArtist% - %songAlbum%' . "\n" .
            '%playIcon% %currentTime% <fg=white>⁌</>%bar%<fg=white>⁍</> %percent:3s%% -%remainingTime% / %songTime%' .
            ' %shuffleIcon% %repeatIcon%'
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

    protected function makeCoverProgressBar()
    {
        $progress = new ProgressBar($this->output, 100);
        $progress->setBarCharacter('<fg=blue>⁍</>');
        $progress->setEmptyBarCharacter('<fg=white>⁍</>');
        $progress->setProgressCharacter('<fg=green>⁍</>');
        $progress->setBarWidth(50);
        $progress->setFormat("Apple Remove Console by Panlatent\n\nEnjoy it!\n\n%bar%");

        return $progress;
    }

    protected function getTimeTag($time)
    {
        return preg_replace('#^0(?=\d+)#', '', date('i:s', $time));
    }

    protected function getPlayIcon()
    {
        switch ($this->playStatue->playStatus) {
            case PlayStatus::PLAY:
                return '<fg=blue>Play</>'; // ▶
            case PlayStatus::PAUSE:
                return '<fg=yellow>PAUSE</>'; // ⏸
            case PlayStatus::STOP:
                return '<fg=red>STOP</>'; // ⏹
            default:
                throw new Exception('Unknown play status');
        }
    }

    protected function getShuffleIcon()
    {
        if ($this->playStatue->shuffle == PlayStatus::SHUFFLE) {
            return '<fg=blue>Shuffle</>'; // 🔀
        }

        return '----';
    }

    protected function getRepeatIcon()
    {
        switch ($this->playStatue->repeat) {
            case PlayStatus::NOT_LOOP:
                return '----';
            case PlayStatus::SINGLE_LOOP:
                return '<fg=yellow>Single</>'; // 🔂
            case PlayStatus::LOOP:
                return '<fg=blue>Loop</>'; // 🔁
            default:
                throw new Exception('Unknown loop type');
        }
    }
}