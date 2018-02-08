<?php

namespace Ruwork\ApiClientTools\Hydrator;

final class PhpDocResultHydrator implements HydratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function hydrate($data, $class)
    {
        if ('[]' === substr($class, -2)) {
            return $this->createCollection($data, substr($class, 0, -2));
        }

        if (null === $data) {
            return null;
        }

        if (!is_array($data)) {
            throw new \UnexpectedValueException(
                sprintf('Expected null or array, %s given.', gettype($data))
            );
        }

        return $this->createObject($data, $class);
    }

    /**
     * @param array|\Traversable $data
     * @param string             $class
     *
     * @throws \UnexpectedValueException
     *
     * @return array|\Traversable
     */
    private function createCollection($data, $class)
    {
        if (is_array($data)) {
            $result = [];

            foreach ($data as $key => $value) {
                $result[$key] = $this->hydrate($value, $class);
            }

            return $result;
        }

        if ($data instanceof \Traversable) {
            return new HydratingIterator($this, $data, $class);
        }

        throw new \UnexpectedValueException(
            sprintf('Expected array or \Traversable, %s given.', gettype($data))
        );
    }

    /**
     * @param array  $data
     * @param string $class
     *
     * @throws \UnexpectedValueException
     *
     * @return AbstractPhpDocResult
     */
    private function createObject($data, $class)
    {
        if (!class_exists($class)) {
            throw new \UnexpectedValueException(sprintf('Class %s does not exist.', $class));
        }

        if (!is_subclass_of($class, AbstractPhpDocResult::class)) {
            throw new \UnexpectedValueException(
                sprintf('PHPDoc result class %s must extend %s.', $class, AbstractPhpDocResult::class)
            );
        }

        foreach ($class::getMap() as $property => $propertyClass) {
            if (isset($data[$property])) {
                $data[$property] = $this->hydrate($data[$property], $propertyClass);
            }
        }

        return new $class($data);
    }
}
