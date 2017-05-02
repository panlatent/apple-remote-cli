<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Support;

use Panlatent\Daap\ElementFactory;

class ExtraContentTypes extends ElementFactory
{
    protected $extra = [];

    public function __construct()
    {
        $this->contentTypes = array_merge($this->contentTypes, $this->extra);
    }
}