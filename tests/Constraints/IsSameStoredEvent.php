<?php

namespace Tests\Constraints;

use EventSnoozer\StoredEvent\StoredEvent;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;
use ReflectionProperty;
use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Comparator\Factory;

class IsSameStoredEvent extends Constraint
{
    /**
     * @var StoredEvent
     */
    private $storedEvent;

    /**
     * IsSameStoredEvent constructor.
     * @param StoredEvent $storedEvent
     */
    public function __construct(StoredEvent $storedEvent)
    {
        parent::__construct();

        $this->storedEvent = $storedEvent;
    }

    public function evaluate($other, $description = '', $returnResult = false)
    {
        if ($this->storedEvent === $other) {
            return true;
        }

        $comparatorFactory = Factory::getInstance();
        $class = new \ReflectionClass($this->storedEvent);
        $otherObj = new \ReflectionObject($other);

        try {
            $reflectionProperties = $class->getProperties(
                ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PRIVATE
            );
            foreach ($reflectionProperties as $property) {
                $property->setAccessible(true);
                $otherProperty = $otherObj->getProperty($property->getName());
                $otherProperty->setAccessible(true);
                $comparator = $comparatorFactory->getComparatorFor(
                    $property->getValue($this->storedEvent),
                    $otherProperty->getValue($other)
                );

                if ('runtime' === $property->getName()) {
                    $comparator->assertEquals(
                        $property->getValue($this->storedEvent),
                        $otherProperty->getValue($other),
                        1
                    );
                } else {
                    $comparator->assertEquals(
                        $property->getValue($this->storedEvent),
                        $otherProperty->getValue($other)
                    );
                }
            }



        } catch (ComparisonFailure $f) {
            if ($returnResult) {
                return false;
            }

            throw new ExpectationFailedException(
                \trim($description . "\n" . $f->getMessage()),
                $f
            );
        }

        return true;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString(): string
    {
        return \sprintf(
            'is equal to %s',
            $this->exporter->export($this->storedEvent)
        );
    }
}
