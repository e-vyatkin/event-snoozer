<?php

namespace EventSnoozer\StoredEvent;

interface StoredEventInterface
{
    /**
     * @param \DateTime $runtime
     * @return StoredEventInterface
     */
    public function setRuntime(\DateTime $runtime);

    /**
     * @return \DateTime
     */
    public function getRuntime();
}
