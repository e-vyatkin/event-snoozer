<?php

namespace EventSnoozer\StoredEvent;

class StoredEvent implements StoredEventInterface
{
    /**
     * @var int|string
     */
    protected $id;

    /**
     * @var \DateTime
     */
    protected $runtime;

    /**
     * @var string
     */
    protected $className;

    /**
     * @var int
     */
    protected $priority;

    /**
     * @var array
     */
    protected $additionalData;

    /**
     * @var string
     */
    protected $eventName;

    /**
     * StoredEvent constructor.
     */
    public function __construct()
    {
        $this->priority = 0;
        $this->runtime = new \DateTime();
        $this->additionalData = array();
    }

    /**
     * @param int|string $id
     * @return StoredEventInterface
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \DateTime|string $runtime
     * @return StoredEventInterface
     */
    public function setRuntime($runtime)
    {
        if (is_string($runtime)) {
            $runtime = new \DateTime($runtime);
        }
        $this->runtime = $runtime;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getRuntime()
    {
        return $this->runtime instanceof \DateTime ? $this->runtime : new \DateTime();
    }

    /**
     * @param string $className
     * @return StoredEventInterface
     */
    public function setEventClass($className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * @return string
     */
    public function getEventClass()
    {
        return $this->className;
    }

    /**
     * @param string $eventName
     * @return StoredEventInterface
     */
    public function setEventName($eventName)
    {
        $this->eventName = $eventName;

        return $this;
    }

    /**
     * @return string
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * @param int $priority
     * @return StoredEventInterface
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param array $additionalData
     * @return StoredEventInterface
     */
    public function setAdditionalData(array $additionalData)
    {
        $this->additionalData = $additionalData;

        return $this;
    }

    /**
     * @return array
     */
    public function getAdditionalData()
    {
        return $this->additionalData;
    }
}
