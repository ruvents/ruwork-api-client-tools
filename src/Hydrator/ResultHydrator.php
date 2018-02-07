<?php

namespace Ruwork\ApiClientTools\Hydrator;

final class ResultHydrator implements HydratorInterface
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
            return new TraversableCache($this->generateResult($data, $class));
        }

        throw new \UnexpectedValueException(
            sprintf('Expected array or \Traversable, %s given.', gettype($data))
        );
    }

    /**
     * @param \Traversable $data
     * @param string       $class
     *
     * @return \Generator
     */
    private function generateResult(\Traversable $data, $class)
    {
        foreach ($data as $key => $value) {
            yield $key => $this->hydrate($value, $class);
        }
    }

    /**
     * @param array  $data
     * @param string $class
     *
     * @throws \UnexpectedValueException
     *
     * @return AbstractResult
     */
    private function createObject($data, $class)
    {
        if (!class_exists($class)) {
            throw new \UnexpectedValueException(sprintf('Class %s does not exist.', $class));
        }

        if (!is_subclass_of($class, AbstractResult::class)) {
            throw new \UnexpectedValueException(
                sprintf('Result class %s must extend %s.', $class, AbstractResult::class)
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
