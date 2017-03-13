<?php

namespace EventSnoozer;

use EventSnoozer\EventSnoozer\EventStorageInterface;
use EventSnoozer\StoredEvent\StoredEvent;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventSnoozer
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var EventStorageInterface
     */
    private $eventStorage;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @param EventStorageInterface $eventStorage
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, EventStorageInterface $eventStorage)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->eventStorage = $eventStorage;
    }

    /**
     * @param Event $event
     * @param string $snoozeTime
     * @return bool
     */
    public function snoozeEvent(Event $event, $snoozeTime = '1 min')
    {
        $storedEvent = new StoredEvent();

        return $this->eventStorage->saveEvent($storedEvent);
    }

    /**
     * @param Event $event
     * @return Event
     */
    public function dispatchEvent(Event $event)
    {
        return $this->eventDispatcher->dispatch($event);
    }
}
