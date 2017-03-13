<?php

namespace EventSnoozer\EventSnoozer;

use EventSnoozer\StoredEvent\StoredEventInterface;

interface EventStorageInterface
{
    /**
     * @param StoredEventInterface $event
     * @return bool
     */
    public function saveEvent(StoredEventInterface $event);
}
