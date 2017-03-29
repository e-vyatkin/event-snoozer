<?php

namespace Tests\EventSnoozer;

class TestEventListener
{
    /**
     * @var TestEvent[]
     */
    public $firedEvents;

    public function __construct()
    {
        $this->firedEvents = array();
    }

    public function onTestEvent(TestEvent $event)
    {
        $this->firedEvents[] = $event;

        return $event;
    }
}
