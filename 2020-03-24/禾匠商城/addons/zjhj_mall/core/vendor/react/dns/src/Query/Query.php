<?php

namespace React\Dns\Query;

class Query
{
    public $name;
    public $type;
    public $class;

    /**
     * @deprecated still used internally for BC reasons, should not be used externally.
     */
    public $currentTime;

    /**
     * @param string   $name        query name, i.e. hostname to look up
     * @param int      $type        query type, see Message::TYPE_* constants
     * @param int      $class       query class, see Message::CLASS_IN constant
     * @param int|null $currentTime (deprecated) still used internally, should not be passed explicitly anymore.
     */
    public function __construct($name, $type, $class, $currentTime = null)
    {
        if($currentTime === null) {
            $currentTime = time();
        }

        $this->name = $name;
        $this->type = $type;
        $this->class = $class;
        $this->currentTime = $currentTime;
    }
}
