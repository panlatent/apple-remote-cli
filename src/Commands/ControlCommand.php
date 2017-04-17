<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Commands;

use Panlatent\AppleRemoteCli\PlayControl;
use Panlatent\AppleRemoteCli\RemoteClient;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class ControlCommand extends Command
{
    /**
     * @var PlayControl
     */
    protected $control;

    protected function configure()
    {
        parent::configure();
        $this->addOption('auth', null, InputOption::VALUE_REQUIRED, 'authorization code');
        $this->addOption('host', null, InputOption::VALUE_OPTIONAL, 'server host', '127.0.0.1');
        $this->addOption('port', null, InputOption::VALUE_OPTIONAL, 'server port', '3689');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        $authorizationCode = $input->getOption('auth');
        $remote = new RemoteClient($input->getOption('host'), $input->getOption('port'));
        $remote->login($authorizationCode);

        $this->control = new PlayControl($remote);
    }
}