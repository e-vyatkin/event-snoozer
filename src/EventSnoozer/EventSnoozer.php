<?php

namespace EventSnoozer;

use EventSnoozer\EventSnoozer\EventStorageInterface;
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
     * @param RealEvent $event
     * @param string $snoozeTime
     * @return bool
     */
    public function snoozeEvent($eventName, RealEvent $event, $snoozeTime = '+1 min')
    {
        $storedEvent = new StoredEvent();
        $storedEvent->setEventClass(get_class($event))
            ->setEventName($eventName)
            ->setRuntime(new \DateTime($snoozeTime))
            ->setPriority($event->getPriority())
            ->setAdditionalData($event->getAdditionalData());

        return $this->eventStorage->saveEvent($storedEvent);
    }

    /**
     * @return bool
     */
    public function dispatchSnoozedEvent()
    {
        $event = $this->eventStorage->fetchEvent();

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

            $this->eventStorage->removeEvent($event);
        }

        return true;
    }

    /**
     * @param int $count
     * @return int
     */
    public function dispatchMultipleSnoozedEvents($count = 1)
    {
        $events = $this->eventStorage->fetchMultipleEvents($count);

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
            $this->eventStorage->removeEvent($event);
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
        return $this->eventDispatcher->dispatch($eventName, $event);
    }
}
