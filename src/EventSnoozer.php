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
    public function snoozeEvent(string $eventName, Event $event, string $snoozeTime = '+1 min'): bool
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
    public function dispatchSnoozedEvent(): bool
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
    public function dispatchMultipleSnoozedEvents(int $count = 1): int
    {
        $events = $this->getEventStorage()->fetchMultipleEvents($count);

        $dispatched = 0;
        foreach ($events as $event) {
            /** @var StoredEventInterface $event */
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
    public function dispatchEvent(string $eventName, Event $event): Event
    {
        return $this->getEventDispatcher()->dispatch($eventName, $event);
    }

    /**
     * @return EventDispatcherInterface
     */
    protected function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    /**
     * @return EventStorageInterface
     */
    protected function getEventStorage(): EventStorageInterface
    {
        return $this->eventStorage;
    }
}
