<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Commands;

use Panlatent\AppleRemoteCli\Commands\Ui\PlayerUi;
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
        $ui = new PlayerUi($input, $output, $this->control);
        $ui->cover();
        $ui->show();
    }
}