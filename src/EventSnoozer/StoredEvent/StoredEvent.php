<?php

namespace EventSnoozer\StoredEvent;

class StoredEvent implements StoredEventInterface
{
    /**
     * @var \DateTime
     */
    protected $runtime;

    /**
     * @param \DateTime $runtime
     * @return StoredEventInterface
     */
    public function setRuntime(\DateTime $runtime)
    {
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
}
