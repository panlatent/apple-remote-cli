<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Commands\Control\Ui;

use GuzzleHttp\Exception\ClientException;
use Panlatent\AppleRemoteCli\Commands\Exception;
use Panlatent\AppleRemoteCli\PlayControl;
use Panlatent\AppleRemoteCli\PlayStatus;
use SplStack;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WatchPlayUi
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
    protected $initialized = false;

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
            $coverBar->advance($i*10);
            usleep(100000);
        }
        $coverBar->clear();
    }

    public function init()
    {
        $this->getPlayStatus();
        $this->refreshUi();
        $this->initialized = true;
    }

    public function show()
    {
        if ( ! $this->initialized) {
            $this->init();
        }
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

            if ($this->updated) {
                $this->freeUi();
                $this->refreshUi();
                $this->updated = false;
            } else {
                $this->updateTimeArea();
            }

            if ($requestLoop == 4) {
                $this->progress->setProgress($this->currentTime);
            } else {
                $this->progress->advance();
            }
            $requestLoop -= 1;
            $this->currentTime += 1;
            if (microtime(true) < $markTime + 1) {
                time_sleep_until($markTime + 1);
            }
        } while (true);

        $this->freeUi();
    }

    protected function refreshUi()
    {
        $this->progress = $this->makeProgressBar($this->time);
        $this->progress->setMessage($this->playStatue->songName, 'songName');
        $this->progress->setMessage($this->playStatue->songArtist, 'songArtist');
        $this->progress->setMessage(date('i:s', $this->time), 'songTime');
        switch ($this->playStatue->playStatus) {
            case PlayStatus::PLAY:
                $button = '️️▶️';
                break;
            case PlayStatus::PAUSE:
                $button = '⏸';
                break;
            case PlayStatus::STOP:
                $button = '⏹';
                break;
            default:
                throw new Exception('Unknown play status');
        }
        $this->progress->setMessage($button, 'pauseState');
        $this->updateTimeArea();
        $this->progress->start();
    }

    protected function freeUi()
    {
        if ($this->progress) {
            $this->progress->finish();
            $this->progress->clear();
        }
    }

    protected function updatePlayingProgressUi()
    {

    }

    protected function updateTimeArea()
    {
        $this->progress->setMessage(date('i:s', $this->currentTime), 'currentTime');
        $this->progress->setMessage(date('i:s', $this->progress->getMaxSteps() - $this->progress->getProgress()),
            'remainingTime');
    }

    protected function checkExpireArea()
    {

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
        if (count($this->uiChangeStack) < 2) {
            $this->updated = false;
            return;
        }

        $top = $this->uiChangeStack->pop();
        if ($top !== $this->uiChangeStack->pop()) {
            $this->updated = true;
        }
        $this->uiChangeStack->push($top);
    }

    protected function makeProgressBar($max)
    {
        $progress = new ProgressBar($this->output, $max);
        $progress->setBarCharacter('<fg=blue>⁍</>');
        $progress->setEmptyBarCharacter('<fg=white>⁍</>');
        $progress->setProgressCharacter('<fg=green>⁍</>');
        $progress->setBarWidth(50);
        $progress->setFormat(
            '%songName% %songArtist% %songTime% %pauseState%' . "\n\n" .
            '%currentTime% <fg=white>⁌</>%bar%<fg=white>⁍</> %percent:3s%% -%remainingTime% / %songTime%'
        );

        return $progress;
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
}