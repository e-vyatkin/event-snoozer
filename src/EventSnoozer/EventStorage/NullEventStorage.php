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
}
