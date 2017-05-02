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
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        $remote = new RemoteClient($this->config['connect']['host'], $this->config['connect']['port']);
        $remote->login($this->config['connect']['auth']);
        $this->control = new PlayControl($remote);
    }
}