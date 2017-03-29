<?php

namespace Tests\EventSnoozer\EventStorage;

use EventSnoozer\EventStorage\MemoryEventStorage;
use EventSnoozer\StoredEvent\StoredEvent;
use Tests\EventSnoozer\TestEvent;

class MemoryEventStorageTest extends \PHPUnit_Framework_TestCase
{
    public function testSaveEvent()
    {
        $storedEvent = new StoredEvent();
        $storedEvent->setEventName(TestEvent::NAME)
            ->setEventClass('Tests\EventSnoozer\EventSnoozerTest\TestEvent')
            ->setAdditionalData(array('key' => 'value'))
            ->setRuntime(new \DateTime('+1 day'))
            ->setPriority(123)
            ->setId(456);

        $memoryEventStorage = new MemoryEventStorage();
        $memoryEventStorage->saveEvent($storedEvent);

        $reflectionClass = new \ReflectionClass($memoryEventStorage);
        $reflectionProperty = $reflectionClass->getProperty('storedEvents');
        $reflectionProperty->setAccessible(true);
        $storedEvents = $reflectionProperty->getValue($memoryEventStorage);

        self::assertSame(array($storedEvent), $storedEvents);
    }

    public function testFetchEvent()
    {
        $futureEvent = new StoredEvent();
        $futureEvent->setEventName(TestEvent::NAME)
            ->setEventClass('Tests\EventSnoozer\EventSnoozerTest\TestEvent')
            ->setAdditionalData(array('key' => 'value'))
            ->setRuntime(new \DateTime('+1 day'))
            ->setPriority(123)
            ->setId(456);
        $pastEvent = new StoredEvent();
        $pastEvent->setEventName(TestEvent::NAME)
            ->setEventClass('Tests\EventSnoozer\EventSnoozerTest\TestEvent')
            ->setAdditionalData(array('key' => 'value'))
            ->setRuntime(new \DateTime('-1 day'))
            ->setPriority(123)
            ->setId(654);

        $memoryEventStorage = new MemoryEventStorage();
        $reflectionClass = new \ReflectionClass($memoryEventStorage);
        $reflectionProperty = $reflectionClass->getProperty('storedEvents');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($memoryEventStorage, array($futureEvent, $pastEvent));

        $fetchedEvent = $memoryEventStorage->fetchEvent();
        self::assertSame($pastEvent, $fetchedEvent);
    }

    public function testFetchMultipleEvents()
    {
        $futureEvent = new StoredEvent();
        $futureEvent->setEventName(TestEvent::NAME)
            ->setEventClass('Tests\EventSnoozer\EventSnoozerTest\TestEvent')
            ->setAdditionalData(array('key' => 'value'))
            ->setRuntime(new \DateTime('+1 day'))
            ->setPriority(123)
            ->setId(234);
        $pastEvent1 = new StoredEvent();
        $pastEvent1->setEventName(TestEvent::NAME)
            ->setEventClass('Tests\EventSnoozer\EventSnoozerTest\TestEvent')
            ->setAdditionalData(array('key' => 'value'))
            ->setRuntime(new \DateTime('-1 day'))
            ->setPriority(123)
            ->setId(345);
        $pastEvent2 = new StoredEvent();
        $pastEvent2->setEventName(TestEvent::NAME)
            ->setEventClass('Tests\EventSnoozer\EventSnoozerTest\TestEvent')
            ->setAdditionalData(array('key' => 'value'))
            ->setRuntime(new \DateTime('-2 day'))
            ->setPriority(10)
            ->setId(456);

        $memoryEventStorage = new MemoryEventStorage();
        $reflectionClass = new \ReflectionClass($memoryEventStorage);
        $reflectionProperty = $reflectionClass->getProperty('storedEvents');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($memoryEventStorage, array($futureEvent, $pastEvent1, $pastEvent2));

        $fetchedEvents = $memoryEventStorage->fetchMultipleEvents(1);
        self::assertSame(array($pastEvent1), $fetchedEvents);

        $fetchedEvents = $memoryEventStorage->fetchMultipleEvents(2);
        self::assertSame(array($pastEvent1, $pastEvent2), $fetchedEvents);

        $fetchedEvents = $memoryEventStorage->fetchMultipleEvents(3);
        self::assertSame(array($pastEvent1, $pastEvent2), $fetchedEvents);
    }

    public function testRemoveEvent()
    {
        $futureEvent = new StoredEvent();
        $futureEvent->setEventName(TestEvent::NAME)
            ->setEventClass('Tests\EventSnoozer\EventSnoozerTest\TestEvent')
            ->setAdditionalData(array('key' => 'value'))
            ->setRuntime(new \DateTime('+1 day'))
            ->setPriority(123)
            ->setId(234);
        $pastEvent = new StoredEvent();
        $pastEvent->setEventName(TestEvent::NAME)
            ->setEventClass('Tests\EventSnoozer\EventSnoozerTest\TestEvent')
            ->setAdditionalData(array('key' => 'value'))
            ->setRuntime(new \DateTime('-1 day'))
            ->setPriority(123)
            ->setId(345);

        $memoryEventStorage = new MemoryEventStorage();
        $reflectionClass = new \ReflectionClass($memoryEventStorage);
        $reflectionProperty = $reflectionClass->getProperty('storedEvents');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($memoryEventStorage, array($futureEvent, $pastEvent));

        $memoryEventStorage->removeEvent($pastEvent);
        $leftEvents = $reflectionProperty->getValue($memoryEventStorage);
        self::assertSame(array($futureEvent), $leftEvents);

        $memoryEventStorage->removeEvent($futureEvent);
        $leftEvents = $reflectionProperty->getValue($memoryEventStorage);
        self::assertSame(array(), $leftEvents);
    }

    public function testWhitelistRestrictions()
    {
        $pastEvent1 = new StoredEvent();
        $pastEvent1->setEventName('test.event1')
            ->setEventClass('Tests\EventSnoozer\EventSnoozerTest\TestEvent')
            ->setAdditionalData(array('key' => 'value'))
            ->setRuntime(new \DateTime('-1 day'))
            ->setPriority(123)
            ->setId(345);
        $pastEvent2 = new StoredEvent();
        $pastEvent2->setEventName('test.event2')
            ->setEventClass('Tests\EventSnoozer\EventSnoozerTest\TestEvent')
            ->setAdditionalData(array('key' => 'value'))
            ->setRuntime(new \DateTime('-2 day'))
            ->setPriority(10)
            ->setId(456);

        $memoryEventStorage = new MemoryEventStorage();
        $reflectionClass = new \ReflectionClass($memoryEventStorage);
        $reflectionProperty = $reflectionClass->getProperty('storedEvents');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($memoryEventStorage, array($pastEvent1, $pastEvent2));
        $memoryEventStorage->setWhitelistEvents(array('test.event2'));

        $fetchedEvents = $memoryEventStorage->fetchMultipleEvents(2);
        self::assertSame(array($pastEvent2), $fetchedEvents);
    }

    public function testBlacklistRestrictions()
    {
        $pastEvent1 = new StoredEvent();
        $pastEvent1->setEventName('test.event1')
            ->setEventClass('Tests\EventSnoozer\EventSnoozerTest\TestEvent')
            ->setAdditionalData(array('key' => 'value'))
            ->setRuntime(new \DateTime('-1 day'))
            ->setPriority(123)
            ->setId(345);
        $pastEvent2 = new StoredEvent();
        $pastEvent2->setEventName('test.event2')
            ->setEventClass('Tests\EventSnoozer\EventSnoozerTest\TestEvent')
            ->setAdditionalData(array('key' => 'value'))
            ->setRuntime(new \DateTime('-2 day'))
            ->setPriority(10)
            ->setId(456);

        $memoryEventStorage = new MemoryEventStorage();
        $reflectionClass = new \ReflectionClass($memoryEventStorage);
        $reflectionProperty = $reflectionClass->getProperty('storedEvents');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($memoryEventStorage, array($pastEvent1, $pastEvent2));
        $memoryEventStorage->setBlacklistEvents(array('test.event2'));

        $fetchedEvents = $memoryEventStorage->fetchMultipleEvents(2);
        self::assertSame(array($pastEvent1), $fetchedEvents);
    }
}
