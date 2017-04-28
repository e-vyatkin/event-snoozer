<?php

namespace EventSnoozer\StoredEvent;

interface StoredEventInterface
{
    /**
     * @param int|string $id
     * @return StoredEventInterface
     */
    public function setId($id): StoredEventInterface;

    /**
     * @return int|string
     */
    public function getId();

    /**
     * @param \DateTime|string $runtime
     * @return StoredEventInterface
     */
    public function setRuntime($runtime): StoredEventInterface;

    /**
     * @return \DateTime
     */
    public function getRuntime(): \DateTime;

    /**
     * @param string $className
     * @return StoredEventInterface
     */
    public function setEventClass(string $className): StoredEventInterface;

    /**
     * @return string
     */
    public function getEventClass(): string;

    /**
     * @param string $eventName
     * @return StoredEventInterface
     */
    public function setEventName(string $eventName): StoredEventInterface;

    /**
     * @return string
     */
    public function getEventName(): string;

    /**
     * @param int $priority
     * @return StoredEventInterface
     */
    public function setPriority(int $priority): StoredEventInterface;

    /**
     * @return int
     */
    public function getPriority(): int;

    /**
     * @param array $additionalData
     * @return StoredEventInterface
     */
    public function setAdditionalData(array $additionalData): StoredEventInterface;

    /**
     * @return array
     */
    public function getAdditionalData(): array;
}
