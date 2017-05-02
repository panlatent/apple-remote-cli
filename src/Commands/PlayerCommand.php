<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Commands;

use Panlatent\AppleRemoteCli\Player\Player;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PlayerCommand extends ControlCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('player')
            ->setDescription('Run a remote player')
            ->addOption('gui', null, InputOption::VALUE_NONE, 'Open a gui window instead of character ui');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        $player = new Player($input, $output, $this->control);
        if ($input->getOption('gui')) {
            if (version_compare(PHP_VERSION, '7.0', '<') ||
                in_array('gui', get_loaded_extensions())) {
                $output->writeln('<error>Open a gui window failed: php version no less than 7.0 and installed gui extension</error>');
                return;
            }

            $player->withGui();
        }
        $player->run();
    }
}