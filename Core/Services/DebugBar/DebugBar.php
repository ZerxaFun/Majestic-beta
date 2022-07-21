<?php

namespace Core\Services\DebugBar;

use ArrayAccess;
use Core\Services\DebugBar\DataCollector\DataCollectorInterface;
use RuntimeException;

/**
 * Main DebugBar object
 *
 * Manages data collectors. DebugBar provides an array-like access
 * to collectors by name.
 *
 */
class DebugBar implements ArrayAccess
{
    protected array $collectors = [];

    protected mixed $data = [];



    /**
     * Adds a data collector
     *
     * @param DataCollectorInterface $collector
     *
     * @throws RuntimeException
     * @return $this
     */
    final public function addCollector(DataCollectorInterface $collector): static
    {
        if ($collector->getName() === '__meta') {
            throw new RuntimeException("'__meta' is a reserved name and cannot be used as a collector name");
        }
        if (isset($this->collectors[$collector->getName()])) {
            throw new RuntimeException("'{$collector->getName()}' is already a registered collector");
        }
        $this->collectors[$collector->getName()] = $collector;
        return $this;
    }

    /**
     * Checks if a data collector has been added
     *
     * @param string $name
     * @return boolean
     */
    final public function hasCollector(string $name): bool
    {
        return isset($this->collectors[$name]);
    }

    /**
     * Returns a data collector
     *
     * @param string $name
     * @return DataCollectorInterface
     * @throws RuntimeException
     */
    final public function getCollector(string $name): DataCollectorInterface
    {
        if (!isset($this->collectors[$name])) {
            throw new RuntimeException("'$name' is not a registered collector");
        }
        return $this->collectors[$name];
    }


    /**
     * @return array
     */
    final public function collect(): array
    {
        foreach ($this->collectors as $name => $collector) {
            $this->data[$name] = $collector->collect();
        }



        return $this->data;
    }

    /**
     * @return array
     */
    final public function getData(): array
    {
        if ($this->data === []) {
            $this->collect();
        }
        return $this->data;
    }

    /**
     * @return JavascriptRenderer
     */
    final public function getJavascriptRenderer(): JavascriptRenderer
    {

        return new JavascriptRenderer($this);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @return void
     * @throws RuntimeException
     */
    final public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new RuntimeException("DebugBar[] is read-only");
    }

    /**
     * @param mixed $offset
     * @return DataCollectorInterface
     */
    final public function offsetGet(mixed $offset): DataCollectorInterface
    {
        return $this->getCollector($offset);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    final public function offsetExists(mixed  $offset): bool
    {
        return $this->hasCollector($offset);
    }

    /**
     * @param mixed $offset
     * @return void
     * @throws RuntimeException
     */
    final public function offsetUnset(mixed $offset): void
    {
        throw new RuntimeException("DebugBar[] is read-only");
    }
}
