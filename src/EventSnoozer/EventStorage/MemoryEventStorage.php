<?php

namespace EventSnoozer\EventStorage;

use EventSnoozer\StoredEvent\StoredEventInterface;

class MemoryEventStorage implements EventStorageInterface
{
    /**
     * @var StoredEventInterface[]
     */
    private $storedEvents;

    public function __construct()
    {
        $this->storedEvents = array();
    }

    /**
     * @param StoredEventInterface $event
     * @return bool
     */
    public function saveEvent(StoredEventInterface $event)
    {
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
            if ($event->getRuntime() < $now) {
                return $event;
            }
        }

        return null;
    }

    /**
     * @param int $count
     * @return StoredEventInterface[]
     */
    public function fetchMultipleEvents($count = 1)
    {
        $this->sortEvents();

        $events = array();
        $now = new \DateTime();
        foreach ($this->storedEvents as $event) {
            if ($event->getRuntime() < $now) {
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
    public function removeEvent(StoredEventInterface $event)
    {
        foreach ($this->storedEvents as $key => $storedEvent) {
            if ($storedEvent === $event) {
                unset($this->storedEvents[$key]);
                return true;
            }
        }

        return false;
    }

    private function sortEvents()
    {
        usort($this->storedEvents, function (StoredEventInterface $a, StoredEventInterface $b) {
            if ($a->getPriority() < $b->getPriority()) {
                return 1;
            } elseif ($a->getPriority() > $b->getPriority()) {
                return -1;
            } else {
                return $a->getRuntime() < $b->getRuntime() ? -1 : 1;
            }
        });
    }
}
