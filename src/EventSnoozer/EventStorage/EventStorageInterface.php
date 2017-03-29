<?php

namespace EventSnoozer\EventStorage;

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

    /**
     * @param array $eventNames
     */
    public function setWhitelistEvents(array $eventNames);

    /**
     * @param array $eventNames
     */
    public function setBlacklistEvents(array $eventNames);
}
