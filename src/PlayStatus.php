<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli;

use Panlatent\DigitalAudio\Document;

class PlayStatus
{
    const STOP = 2;
    const PAUSE = 3;
    const PLAY = 4;

    const NOT_SHUFFLE = 0;
    const SHUFFLE = 1;

    const NOT_LOOP = 0;
    const SINGLE_LOOP = 1;
    const LOOP = 2;

    public $playStatus;

    public $shuffle;

    public $repeat;

    public $fullScreen;

    public $visualizer;

    public $nowPlaying;

    public $songName;

    public $songArtist;

    public $songAlbum;

    public $songTime;

    public $songTimeRemaining;

    public $songTimeTotal;

    // more

    public function __construct(Document $status)
    {
        /** @var \Panlatent\DigitalAudio\ElementStorage $status */
        $status = $status->getElements()->one('cmst');
//        var_dump($status->one('carp'));die();
        $this->playStatus = $status->one('caps')->getValue();
        $this->shuffle = $status->one('cash')->getValue();
        $this->repeat = $status->one('carp')->getValue();
        $this->fullScreen = $status->one('cafs')->getValue();
        $this->visualizer = $status->one('cavs')->getValue();
        if ($status->one('cann')) {
            $this->nowPlaying = $status->one('canp')->getValue();
            $this->songName = $status->one('cann')->getValue();
            $this->songArtist = $status->one('cana')->getValue();
            $this->songAlbum = $status->one('canl')->getValue();
            $this->songTime = $status->one('astm')->getValue();
            if ($status->one('cant')) {
                $this->songTimeRemaining = $status->one('cant')->getValue();
            }
            $this->songTimeTotal = $status->one('cast')->getValue();
        }
    }
}