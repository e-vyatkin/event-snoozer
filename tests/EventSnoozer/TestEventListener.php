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
        $this->firedEvents = [];
    }

    public function onTestEvent(TestEvent $event): TestEvent
    {
        $this->firedEvents[] = $event;

        return $event;
    }
}
