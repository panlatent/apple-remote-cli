<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Support;

use Panlatent\DigitalAudio\ElementFactory;
use Panlatent\DigitalAudio\ElementValueType;

class ExtraContentTypes extends ElementFactory
{
    protected $extra = [
        [
            'cmvo',
            'volume',
            'daap.volume',
            ElementValueType::INT,
        ],
        [
            'cant',
            'song time remaining',
            'daap.songtimeremaining',
            ElementValueType::INT,
        ],
        [
            'cast',
            'song time total',
            'daap.songtimetotal',
            ElementValueType::INT,
        ]
    ];

    public function __construct()
    {
        $this->contentTypes = array_merge($this->contentTypes, $this->extra);
    }
}