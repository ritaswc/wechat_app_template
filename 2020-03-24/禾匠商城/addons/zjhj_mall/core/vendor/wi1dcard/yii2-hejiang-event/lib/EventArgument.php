<?php

namespace Hejiang\Event;

use yii\base\ArrayAccessTrait;

class EventArgument implements \IteratorAggregate, \Countable, \ArrayAccess, \JsonSerializable
{
    use ArrayAccessTrait;
    
    /**
     * Event sender (e.g. Controller)
     *
     * @var mixed
     */
    protected $sender;

    /**
     * Event results after listeners resolving
     *
     * @var mixed[]
     */
    protected $results = [];

    /**
     * Event arguments
     *
     * @var mixed[]
     */
    public $data = [];

    public function __construct($sender = null)
    {
        $this->sender = $sender;
    }

    /**
     * Get event sender instance
     *
     * @return void
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Push a resolved result
     *
     * @param mixed $item
     * @return self
     */
    public function pushResult($item)
    {
        $this->results[] = $item;
        return $this;
    }

    /**
     * Get all results from event chain.
     *
     * @return void
     */
    public function getResults()
    {
        return $this->results;
    }

    public function jsonSerialize()
    {
        return $this->data;
    }
}
