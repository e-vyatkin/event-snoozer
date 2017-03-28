<?php

namespace EventSnoozer\EventSnoozer;

use EventSnoozer\StoredEvent\StoredEventInterface;

class NullEventStorage implements EventStorageInterface
{
    /**
     * @param StoredEventInterface $event
     * @return bool
     */
    public function saveEvent(StoredEventInterface $event)
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
    public function fetchMultipleEvents($count = 1)
    {
        return array();
    }
}
