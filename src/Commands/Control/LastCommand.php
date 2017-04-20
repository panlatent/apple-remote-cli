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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LastCommand extends ControlCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('last')
            ->setDescription('Last on iTunes remote');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        $this->control->last();
    }
}