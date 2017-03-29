<?php

namespace EventSnoozer;

use EventSnoozer\EventStorage\EventStorageInterface;
use EventSnoozer\StoredEvent\StoredEvent;
use EventSnoozer\StoredEvent\StoredEventInterface;
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
     * @param string $eventName
     * @param Event $event
     * @param string $snoozeTime
     * @return bool
     */
    public function snoozeEvent($eventName, Event $event, $snoozeTime = '+1 min')
    {
        $storedEvent = new StoredEvent();
        $storedEvent->setEventClass(get_class($event))
            ->setEventName($eventName)
            ->setRuntime(new \DateTime($snoozeTime));
        if ($event instanceof RealEvent) {
            $storedEvent->setPriority($event->getPriority())
                ->setAdditionalData($event->getAdditionalData());
        }

        return $this->getEventStorage()->saveEvent($storedEvent);
    }

    /**
     * @return bool
     */
    public function dispatchSnoozedEvent()
    {
        $event = $this->getEventStorage()->fetchEvent();

        if ($event instanceof StoredEventInterface) {
            if (class_exists($event->getEventClass())) {
                $className = $event->getEventClass();
                $realEvent = new $className();
                if ($realEvent instanceof RealEvent) {
                    $realEvent->setPriority($event->getPriority())
                        ->setAdditionalData($event->getAdditionalData());
                }

                $this->dispatchEvent($event->getEventName(), $realEvent);
            }

            $this->getEventStorage()->removeEvent($event);
        }

        return true;
    }

    /**
     * @param int $count
     * @return int
     */
    public function dispatchMultipleSnoozedEvents($count = 1)
    {
        $events = $this->getEventStorage()->fetchMultipleEvents($count);

        $dispatched = 0;
        foreach ($events as $event) {
            if (class_exists($event->getEventClass())) {
                $className = $event->getEventClass();
                $realEvent = new $className();
                if ($realEvent instanceof RealEvent) {
                    $realEvent->setPriority($event->getPriority())
                        ->setAdditionalData($event->getAdditionalData());
                }

                $this->dispatchEvent($event->getEventName(), $realEvent);
                $dispatched++;
            }
            $this->getEventStorage()->removeEvent($event);
        }

        return $dispatched;
    }

    /**
     * @param string $eventName
     * @param Event $event
     * @return Event
     */
    public function dispatchEvent($eventName, Event $event)
    {
        return $this->getEventDispatcher()->dispatch($eventName, $event);
    }

    /**
     * @return EventDispatcherInterface
     */
    protected function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * @return EventStorageInterface
     */
    protected function getEventStorage()
    {
        return $this->eventStorage;
    }
}
