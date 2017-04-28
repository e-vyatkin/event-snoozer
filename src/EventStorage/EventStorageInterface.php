<?php

namespace EventSnoozer\EventStorage;

use EventSnoozer\StoredEvent\StoredEventInterface;

interface EventStorageInterface
{
    /**
     * @param StoredEventInterface $event
     * @return bool
     */
    public function saveEvent(StoredEventInterface $event): bool;

    /**
     * @return StoredEventInterface
     */
    public function fetchEvent();

    /**
     * @param int $count
     * @return StoredEventInterface[]
     */
    public function fetchMultipleEvents(int $count = 1): array;

    /**
     * @param StoredEventInterface $event
     * @return bool
     */
    public function removeEvent(StoredEventInterface $event): bool;

    /**
     * @param array $eventNames
     */
    public function setWhitelistEvents(array $eventNames);

    /**
     * @param array $eventNames
     */
    public function setBlacklistEvents(array $eventNames);
}
