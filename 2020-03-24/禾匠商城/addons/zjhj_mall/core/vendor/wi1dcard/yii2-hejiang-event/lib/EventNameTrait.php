<?php

namespace Hejiang\Event;

/**
 * Use this trait for using class name as event name.
 */
trait EventNameTrait
{
    public function getName()
    {
        return __CLASS__;
    }
}
