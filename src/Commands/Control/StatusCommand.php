<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Commands\Control;

use GuzzleHttp\Exception\ClientException;
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
        if ($input->getOption('watch')) {
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
        $id = '';
        /** @var ProgressBar $progress */
        $progress = null;
        $playStatue = null;
        $requestLoop = 0;
        do {
            $markTime = microtime(true);
            if ($requestLoop == 0) {
                try {
                    $playStatue = $this->control->getPlayStatus();
                } catch (ClientException $e) {
                    break;
                }
                $requestLoop = 4;
            }
            $time = (int)($playStatue->songTime/1000);
            $current = (int)($time - $playStatue->songTimeRemaining/1000);
            $idUpdate = md5($playStatue->songName . $playStatue->songArtist . $time);
            if ($id != $idUpdate) {
                if ($progress) {
                    $progress->finish();
                    $progress->clear();
                }
                $progress = $this->makeWatchBar($output, $time);
                $progress->setMessage($playStatue->songName, 'songName');
                $progress->setMessage($playStatue->songArtist, 'songArtist');
                $progress->setMessage(date('i:s', $time), 'songTime');
                $progress->setMessage(date('i:s', $current), 'currentTime');
                $progress->setMessage(date('i:s', $progress->getMaxSteps() - $progress->getProgress()), 'remainingTime');
                $progress->start();
                $id = $idUpdate;
            } else {
                $progress->setMessage(date('i:s', $current), 'currentTime');
                $progress->setMessage(date('i:s', $progress->getMaxSteps() - $progress->getProgress()), 'remainingTime');
            }

            if ($requestLoop == 4) {
                $progress->setProgress($current);
            } else {
                $progress->advance();
            }
            time_sleep_until($markTime + 1);
            $requestLoop -= 1;
        } while (1);

        $progress->clear();
    }

    protected function makeWatchBar(OutputInterface $output, $max)
    {
        $progress = new ProgressBar($output, $max);
        $progress->setBarCharacter('<fg=blue>⁍</>');
        $progress->setEmptyBarCharacter('<fg=white>⁍</>');
        $progress->setProgressCharacter('<fg=green>⁍</>');
        $progress->setBarWidth(50);
        $progress->setFormat(
            '%songName% %songArtist% %songTime%' .  "\n\n" .
            '%currentTime% <fg=white>⁌</>%bar%<fg=white>⁍</> %percent:3s%% -%remainingTime% / %songTime%'
        );

        return $progress;
    }
}