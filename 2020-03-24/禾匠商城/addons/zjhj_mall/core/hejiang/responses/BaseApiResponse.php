<?php

namespace app\hejiang;

/**
 * HTTP API Response Class
 */
class BaseApiResponse extends \yii\base\BaseObject implements \ArrayAccess, \IteratorAggregate, \Countable
{
    protected $raw;

    public function __construct($mixed = null)
    {
        switch (true) {
            case $mixed instanceof BaseApiResponse:
                $this->raw = $mixed->raw;
                break;
            default:
                $this->raw = $mixed;
                break;
        }
    }

    public function getRaw()
    {
        return $this->raw;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->raw);
    }

    public function count()
    {
        return count($this->raw);
    }

    public function offsetExists($offset)
    {
        return isset($this->raw[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->raw[$offset]) ? $this->raw[$offset] : null;
    }

    public function offsetSet($offset, $item)
    {
        $this->raw[$offset] = $item;
    }

    public function offsetUnset($offset)
    {
        unset($this->raw[$offset]);
    }
}
