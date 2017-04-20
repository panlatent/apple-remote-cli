<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Player\Ui;

use Panlatent\AppleRemoteCli\PlayStatus;
use Symfony\Component\Console\Helper\ProgressBar;

class CoverUi extends UiAbstract
{
    protected $rate = 100;

    /**
     * @var \Symfony\Component\Console\Helper\ProgressBar
     */
    protected $progress;

    public function handle()
    {
        if (isset($this->model->playState) && $this->model->playState != PlayStatus::STOP) {
            $this->freeUi();
            return false;
        }
        $this->render();

        return true;
    }

    public function render()
    {
        if ( ! $this->progress) {
            $this->progress = $this->makeProgressBar();
            $this->progress->start();
        }

        $this->progress->advance(10);
        if ($this->progress->getProgress() >= 110) {
            $this->progress->setProgress(0);
        }
    }

    protected function freeUi()
    {
        $this->progress->finish();
        $this->progress->clear();
        $this->progress = null;
    }

    protected function makeProgressBar()
    {
        $progress = new ProgressBar($this->output, 100);
        $progress->setBarCharacter('<fg=blue>⁍</>');
        $progress->setEmptyBarCharacter('<fg=white>⁍</>');
        $progress->setProgressCharacter('<fg=green>⁍</>');
        $progress->setBarWidth(50);
        $progress->setFormat("Apple Remote Console by Panlatent\n\nEnjoy it!\n\n%bar%\n");
        $progress->setRedrawFrequency(20);

        return $progress;
    }
}