<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Commands\Control;

use Panlatent\AppleRemoteCli\Commands\ControlCommand;
use Symfony\Component\Console\Helper\ProgressBar;
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
        if ($input->hasOption('watch')) {
            $this->showWatchBar($input, $output);
        } else {
            $playStatue = $this->control->getPlayStatus();
            $output->writeln(
                $playStatue->songName . ' ' . $playStatue->songArtist . ' ' . date('i:s', $playStatue->songTime/1000)
            );
        }
    }

    protected function showWatchBar(InputInterface $input, OutputInterface $output)
    {
        $progress = $this->makeWatchBar($output);
        $id = '';
        do {
            $playStatue = $this->control->getPlayStatus();
            $time = (int)($playStatue->songTime/1000);
            $current = (int)($time - $playStatue->songTimeRemaining/1000);
            $idUpdate = md5($playStatue->songName . $playStatue->songArtist . $time);
            if ($id != $idUpdate) {
                $progress->start($time);
                $progress->setMessage($playStatue->songName, 'songName');
                $progress->setMessage($playStatue->songArtist, 'songArtist');
                $progress->setMessage(date('i:s', $time), 'songTime');
                $id = $idUpdate;
            }
            $progress->setMessage(date('i:s', $current), 'currentTime');
            $progress->setMessage(date('i:s', $progress->getMaxSteps() - $progress->getProgress()), 'remainingTime');

            $progress->setProgress($current); // Can not use advance
            sleep(1);
        } while (1);
    }

    protected function makeWatchBar(OutputInterface $output)
    {
        $progress = new ProgressBar($output);
        $progress->setBarCharacter('<info>⁍</info>');
        $progress->setEmptyBarCharacter('⁍');
        $progress->setProgressCharacter('<info>➣</info>');
        $progress->setBarWidth(50);
        $progress->setFormat(
            '%songName% %songArtist% %songTime%' .  "\n\n" .
            '%currentTime% <info>⁌</info>%bar%⁍ %percent:3s%% -%remainingTime% / %songTime%'
        );

        return $progress;
    }
}