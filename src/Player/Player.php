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
use Panlatent\AppleRemoteCli\Player\Ui\PlayerUi;
use Panlatent\AppleRemoteCli\Player\Ui\PlayerUiModel;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Player implements IntervalTaskInterface
{
    protected $dispatcher;

    protected $input;

    protected $output;

    protected $control;

    protected $ui;

    protected $model;

    protected $handle;

    public function __construct(InputInterface $input, OutputInterface $output, PlayControl $control)
    {
        $this->dispatcher = new IntervalDispatcher(10000);
        $this->dispatcher->addTask($this, 1000 * 1000 * 4);
        $this->input = $input;
        $this->output = $output;
        $this->control = $control;
        $this->model = new PlayerUiModel();
        $this->ui = new PlayerUi($this->dispatcher, $output, $this->model);
        $this->dispatcher->addTask($this->ui, $this->ui->getRate());
    }

    public function run()
    {
        $this->handle = true;
        $this->handle();
        $this->dispatcher->handle();
    }

    public function isHandle()
    {
        return $this->handle;
    }

    public function handle()
    {
        $playStatue = $this->control->getPlayStatus();
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
}