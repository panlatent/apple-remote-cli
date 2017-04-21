<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Player;

use Panlatent\AppleRemoteCli\Ui\ViewModel;

/**
 * @property string $playState        ;
 * @property string $shuffle          ;
 * @property string $repeat           ;
 * @property string $songCurrentTime  ;
 * @property string $songRemainingTime;
 * @property string $songName         ;
 * @property string $songArtist       ;
 * @property string $songAlbum        ;
 * @property string $songTime         ;
 * @property int    $signal           ;
 */
class PlayerViewModel extends ViewModel
{
    const SIGNAL_LAST_SONG = 1;
    const SIGNAL_NEXT_SONG = 2;
    const SIGNAL_START_SONG_TIME = 3;

    protected $where;
}