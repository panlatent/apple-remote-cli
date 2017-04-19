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
use Symfony\Component\Console\Output\OutputInterface;

class PlayerCommand extends ControlCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('player')
            ->setDescription('Run a remote player');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        $player = new Player($input, $output, $this->control);
        $player->run();
    }
}