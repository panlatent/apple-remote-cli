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
use Panlatent\AppleRemoteCli\Commands\Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class VolumeCommand extends ControlCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('volume')
            ->setAliases(['vol'])
            ->setDescription('Set volume on iTunes remote')
            ->addArgument('volume', InputArgument::OPTIONAL, '1-100 or -/+ value or off/min/half/max', false)
            ->addOption('down1', '1', InputOption::VALUE_OPTIONAL, '', false)
            ->addOption('down2', '2', InputOption::VALUE_OPTIONAL, '', false)
            ->addOption('down3', '3', InputOption::VALUE_OPTIONAL, '', false)
            ->addOption('down4', '4', InputOption::VALUE_OPTIONAL, '', false)
            ->addOption('down5', '5', InputOption::VALUE_OPTIONAL, '', false)
            ->addOption('down6', '6', InputOption::VALUE_OPTIONAL, '', false)
            ->addOption('down7', '7', InputOption::VALUE_OPTIONAL, '', false)
            ->addOption('down8', '8', InputOption::VALUE_OPTIONAL, '', false)
            ->addOption('down9', '9', InputOption::VALUE_OPTIONAL, '', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        $volume = $input->getArgument('volume');
        if (false === $volume) { // volume style is -volume?
            $hasDown = false;
            for ($i = 1; $i <= 9; ++$i) {
                if (false !== $input->getOption('down' . $i)) {
                    if (null === ($input->getOption('down' . $i))) {
                        $volume = -$i;
                    } else {
                        $volume = -($i * 10 + $input->getOption('down' . $i));
                    }
                    $hasDown = true;
                    break;
                }
            }
            if ( ! $hasDown) {
                throw new Exception('Volume value can\'t be empty, using 1-100 or -/+ value or off/min/max');
            }
        } else {
            $volume = $input->getArgument('volume');
        }

        if ($volume === 'off' || $volume === 'min') {
            $volume = 0;
        } elseif ($volume === 'half') {
            $volume = 50;
        } elseif ($volume === 'max') {
            $volume = 100;
        } elseif (preg_match('#^([-+])\s*(\d+)$#u', $volume, $match)) {
            $volume = $this->control->getVolume();
            $change = $match[1] == '+' ? (int)$match[2] : -(int)$match[2];
            $volume += $change;
            if ($volume < 0) {
                $volume = 0;
            } elseif ($volume > 100) {
                $volume = 100;
            }
        } elseif (ctype_digit((string)$volume)) {
            if ($volume < 0 && $volume > 100) {
                throw new Exception('Volume range in 0 to 100');
            }
        } else {
            throw new Exception('Unknown volume setting, using 1-100 or -/+ value or off/min/max');
        }

        $this->control->setVolume((int)$volume);
    }
}