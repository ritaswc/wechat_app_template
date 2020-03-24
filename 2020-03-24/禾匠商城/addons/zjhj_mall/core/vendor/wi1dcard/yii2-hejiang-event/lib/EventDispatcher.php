<?php

namespace Hejiang\Event;

use Evenement\EventEmitterInterface;
use Evenement\EventEmitterTrait;

class EventDispatcher extends \yii\base\BaseObject implements EventEmitterInterface
{
    use EventEmitterTrait;

    public function mount(Dispatchable $event, $name = null)
    {
        if ($name === null) {
            $name = $event->getName();
        }
        $this->on($name, function (EventArgument $args) use ($event) {
            $event->onDispatch($args);
        });
    }

    public function dispatch(Event $event, EventArgument $args)
    {
        $name = $event->getName();
        $this->emit($name, [$args]);
    }
}
