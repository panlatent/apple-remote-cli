<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Ui;

abstract class ViewModel
{
    protected $lock;

    protected $properties;

    protected $changes;

    public function __construct()
    {
        $this->properties = [];
        $this->changes = [];
    }

    function __isset($name)
    {
        return isset($this->properties[$name]);
    }

    function __unset($name)
    {
        unset($this->properties[$name]);
    }

    public function __get($name)
    {
        return $this->properties[$name];
    }

    public function __set($name, $value)
    {
        if ( ! isset($this->properties[$name])) {
            $this->changes[$name] = true;
        } elseif ($this->properties[$name] !== $value) {
            $this->changes[$name] = true;
        }

        $this->properties[$name] = $value;
    }

    public function reentry()
    {
        $this->changes = array_flip(array_keys($this->properties));
    }

    public function pop()
    {
        $changes = array_keys($this->changes);

        if ( ! $this->lock) {
            $this->changes = [];
        }

        return $changes;
    }

    public function isLock()
    {
        return $this->lock;
    }

    public function lock()
    {
        $this->lock = true;
    }

    public function unlock()
    {
        $this->lock = false;
    }
}