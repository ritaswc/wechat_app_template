<?php

namespace Hejiang\Event;

interface Dispatchable
{
    public function onDispatch(EventArgument $arg);
}
