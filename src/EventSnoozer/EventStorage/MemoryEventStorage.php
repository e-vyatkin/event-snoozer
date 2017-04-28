<?php

namespace EventSnoozer\EventStorage;

use EventSnoozer\StoredEvent\StoredEventInterface;

class MemoryEventStorage implements EventStorageInterface
{
    /**
     * @var StoredEventInterface[]
     */
    private $storedEvents;

    /**
     * @var bool | true - whitelist; false - blacklist
     */
    private $mode;

    /**
     * @var array
     */
    private $eventRestrictions;

    public function __construct()
    {
        $this->storedEvents = [];
        $this->mode = false;
        $this->eventRestrictions = [];
    }

    /**
     * @param StoredEventInterface $event
     * @return bool
     */
    public function saveEvent(StoredEventInterface $event): bool
    {
        $event->setId(uniqid('es', true));
        $this->storedEvents[] = $event;

        return true;
    }

    /**
     * @return StoredEventInterface
     */
    public function fetchEvent()
    {
        $this->sortEvents();

        $now = new \DateTime();
        foreach ($this->storedEvents as $event) {
            if ($event->getRuntime() < $now && $this->passedRestrictions($event->getEventName())) {
                return $event;
            }
        }

        return null;
    }

    /**
     * @param int $count
     * @return StoredEventInterface[]
     */
    public function fetchMultipleEvents(int $count = 1): array
    {
        $this->sortEvents();

        $events = [];
        $now = new \DateTime();
        foreach ($this->storedEvents as $event) {
            if ($event->getRuntime() < $now && $this->passedRestrictions($event->getEventName())) {
                $events[] = $event;
                if ($count === count($events)) {
                    break;
                }
            }
        }

        return $events;
    }

    /**
     * @param StoredEventInterface $event
     * @return bool
     */
    public function removeEvent(StoredEventInterface $event): bool
    {
        foreach ($this->storedEvents as $key => $storedEvent) {
            if ($storedEvent->getId() === $event->getId()) {
                unset($this->storedEvents[$key]);

                return true;
            }
        }

        return false;
    }

    /**
     * @param array $eventNames
     */
    public function setWhitelistEvents(array $eventNames)
    {
        $this->eventRestrictions = $eventNames;
        $this->mode = true;
    }

    /**
     * @param array $eventNames
     */
    public function setBlacklistEvents(array $eventNames)
    {
        $this->eventRestrictions = $eventNames;
        $this->mode = false;
    }

    /**
     * @param string $eventName
     * @return bool
     */
    private function passedRestrictions(string $eventName): bool
    {
        return ($this->mode && in_array($eventName, $this->eventRestrictions, true)) ||
            (!$this->mode && !in_array($eventName, $this->eventRestrictions, true));
    }

    private function sortEvents()
    {
        usort(
            $this->storedEvents,
            function (StoredEventInterface $a, StoredEventInterface $b) {
                if ($a->getPriority() < $b->getPriority()) {
                    return 1;
                } elseif ($a->getPriority() > $b->getPriority()) {
                    return -1;
                } else {
                    return $a->getRuntime() < $b->getRuntime() ? -1 : 1;
                }
            }
        );
    }
}
