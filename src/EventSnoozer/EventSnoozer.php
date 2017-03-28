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
     * @param int $priority
     * @param array $additionalData
     * @return bool
     */
    public function snoozeEvent(Event $event, $snoozeTime = '+1 min', $priority = 0, array $additionalData = array())
    {
        $storedEvent = new StoredEvent();
        $storedEvent->setEventClass(get_class($event))
            ->setRuntime(new \DateTime($snoozeTime))
            ->setPriority($priority)
            ->setAdditionalData($additionalData);

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
