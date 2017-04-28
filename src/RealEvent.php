<?php

namespace EventSnoozer;

use Symfony\Component\EventDispatcher\Event;

class RealEvent extends Event
{
    /**
     * @var int
     */
    protected $priority;

    /**
     * @var array
     */
    protected $additionalData;

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority ? (int)$this->priority : 0;
    }

    /**
     * @param int $priority
     * @return RealEvent
     */
    public function setPriority(int $priority): RealEvent
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return array
     */
    public function getAdditionalData(): array
    {
        return is_array($this->additionalData) ? $this->additionalData : [];
    }

    /**
     * @param array $additionalData
     * @return RealEvent
     */
    public function setAdditionalData(array $additionalData): RealEvent
    {
        $this->additionalData = $additionalData;

        return $this;
    }
}
