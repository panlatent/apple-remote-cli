<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Support;

use Panlatent\Daap\Document;

class DigitalAudioResponse extends Document
{
    public static function create($binaryData)
    {
        static $factory = null;
        if ($factory === null) {
            $factory = new ExtraContentTypes();
        }

        return new static($factory, $binaryData);
    }
}