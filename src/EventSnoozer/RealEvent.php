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
    public function getPriority()
    {
        return $this->priority ?: 0;
    }

    /**
     * @param int $priority
     * @return RealEvent
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return array
     */
    public function getAdditionalData()
    {
        return is_array($this->additionalData) ? $this->additionalData : array();
    }

    /**
     * @param array $additionalData
     * @return RealEvent
     */
    public function setAdditionalData(array $additionalData)
    {
        $this->additionalData = $additionalData;

        return $this;
    }
}
