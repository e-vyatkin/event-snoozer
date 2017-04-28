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
        $this->additionalData = [];
    }

    /**
     * @param int|string $id
     * @return StoredEventInterface
     */
    public function setId($id): StoredEventInterface
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
    public function setRuntime($runtime): StoredEventInterface
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
    public function getRuntime(): \DateTime
    {
        return $this->runtime instanceof \DateTime ? $this->runtime : new \DateTime();
    }

    /**
     * @param string $className
     * @return StoredEventInterface
     */
    public function setEventClass(string $className): StoredEventInterface
    {
        $this->className = $className;

        return $this;
    }

    /**
     * @return string
     */
    public function getEventClass(): string
    {
        return (string)$this->className;
    }

    /**
     * @param string $eventName
     * @return StoredEventInterface
     */
    public function setEventName(string $eventName): StoredEventInterface
    {
        $this->eventName = $eventName;

        return $this;
    }

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return (string)$this->eventName;
    }

    /**
     * @param int $priority
     * @return StoredEventInterface
     */
    public function setPriority(int $priority): StoredEventInterface
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return (int)$this->priority;
    }

    /**
     * @param array $additionalData
     * @return StoredEventInterface
     */
    public function setAdditionalData(array $additionalData): StoredEventInterface
    {
        $this->additionalData = $additionalData;

        return $this;
    }

    /**
     * @return array
     */
    public function getAdditionalData(): array
    {
        return $this->additionalData;
    }
}
