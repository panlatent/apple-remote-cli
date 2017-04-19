<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Player\Ui;

class PlayerIo
{
    public function __construct()
    {

    }

    public function run()
    {
        shell_exec('stty -echo time 0 -icanon');
        stream_set_blocking(STDIN, false);
        while (1) {
            $f = fgetc(STDIN);
            echo $f;
        }
        return;
    }
}