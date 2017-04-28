<?php

namespace EventSnoozer\EventStorage;

use EventSnoozer\StoredEvent\StoredEventInterface;

class NullEventStorage implements EventStorageInterface
{
    /**
     * @param StoredEventInterface $event
     * @return bool
     */
    public function saveEvent(StoredEventInterface $event): bool
    {
        return true;
    }

    /**
     * @return StoredEventInterface
     */
    public function fetchEvent()
    {
        return null;
    }

    /**
     * @param int $count
     * @return StoredEventInterface[]
     */
    public function fetchMultipleEvents(int $count = 1): array
    {
        return [];
    }

    /**
     * @param StoredEventInterface $event
     * @return bool
     */
    public function removeEvent(StoredEventInterface $event): bool
    {
        return true;
    }

    /**
     * @param array $eventNames
     */
    public function setWhitelistEvents(array $eventNames)
    {
    }

    /**
     * @param array $eventNames
     */
    public function setBlacklistEvents(array $eventNames)
    {
    }
}
