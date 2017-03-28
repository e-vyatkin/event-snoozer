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

    /**
     * @return StoredEventInterface
     */
    public function fetchEvent();

    /**
     * @param int $count
     * @return StoredEventInterface[]
     */
    public function fetchMultipleEvents($count = 1);

    /**
     * @param StoredEventInterface $event
     * @return bool
     */
    public function removeEvent(StoredEventInterface $event);
}
