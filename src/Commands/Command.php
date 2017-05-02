<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class Command extends \Symfony\Component\Console\Command\Command
{
    protected $config = [];

    protected function configure()
    {
        $this->addOption('auth', null, InputOption::VALUE_OPTIONAL, 'authorization code');
        $this->addOption('host', null, InputOption::VALUE_OPTIONAL, 'server host', '127.0.0.1');
        $this->addOption('port', null, InputOption::VALUE_OPTIONAL, 'server port', '3689');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->loadConfigureFile();
        if ($input->getOption('auth')) {
            $this->config['connect']['auth'] = $input->getOption('auth');
        }
        if ($input->getOption('host')) {
            $this->config['connect']['host'] = $input->getOption('host');
        }
        if ($input->getOption('port')) {
            $this->config['connect']['port'] = $input->getOption('port');
        }
    }

    protected function loadConfigureFile()
    {
        $filename = getenv('HOME') . DIRECTORY_SEPARATOR . '.apple-remote.yml';
        if (is_file($filename)) {
            $this->config = Yaml::parse(file_get_contents($filename));
        }
    }
}