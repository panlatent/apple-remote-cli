<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Commands\Control;

use Panlatent\AppleRemoteCli\Commands\Control\Ui\WatchPlayUi;
use Panlatent\AppleRemoteCli\Commands\ControlCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class StatusCommand extends ControlCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('status')
            ->setDescription('Show current play status')
            ->addOption('watch', 'w', InputOption::VALUE_NONE, 'Listen playing');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        if ($input->getOption('watch')) {
            $this->showWatchPlayUi($input, $output);
        } else {
            $playStatue = $this->control->getPlayStatus();
            $output->writeln(
                $playStatue->songName . ' ' . $playStatue->songArtist . ' ' . date('i:s', $playStatue->songTime/1000)
            );
        }
    }

    protected function showWatchPlayUi(InputInterface $input, OutputInterface $output)
    {
        $ui = new WatchPlayUi($input, $output, $this->control);
        $ui->cover();
        $ui->show();
    }
}