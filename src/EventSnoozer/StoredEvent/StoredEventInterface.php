<?php

namespace EventSnoozer\StoredEvent;

interface StoredEventInterface
{
    /**
     * @param int|string $id
     * @return StoredEventInterface
     */
    public function setId($id);

    /**
     * @return int|string
     */
    public function getId();

    /**
     * @param \DateTime $runtime
     * @return StoredEventInterface
     */
    public function setRuntime(\DateTime $runtime);

    /**
     * @return \DateTime
     */
    public function getRuntime();

    /**
     * @param string $className
     * @return StoredEventInterface
     */
    public function setEventClass($className);

    /**
     * @return string
     */
    public function getEventClass();

    /**
     * @param string $eventName
     * @return StoredEventInterface
     */
    public function setEventName($eventName);

    /**
     * @return string
     */
    public function getEventName();

    /**
     * @param int $priority
     * @return StoredEventInterface
     */
    public function setPriority($priority);

    /**
     * @return int
     */
    public function getPriority();

    /**
     * @param array $additionalData
     * @return StoredEventInterface
     */
    public function setAdditionalData(array $additionalData);

    /**
     * @return array
     */
    public function getAdditionalData();
}
