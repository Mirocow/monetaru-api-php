<?php

namespace AvtoDev\MonetaApi\Support;

use JsonSerializable;
use AvtoDev\MonetaApi\Support\Contracts\Jsonable;
use AvtoDev\MonetaApi\Support\Contracts\Collection;

/**
 * Class AbstractCollection.
 */
abstract class AbstractCollection implements Collection, Jsonable, JsonSerializable
{
    protected $stack    = [];

    protected $position = 0;

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->stack = [];
    }

    /**
     * {@inheritdoc}
     */
    public function copy()
    {
        return clone $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return $this->count() === 0;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->stack);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return $this->stack;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->stack[$this->position];
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return isset($this->stack[$this->position]);
    }

    public function drop($position)
    {
        if (isset($this->stack[$position])) {
            unset($this->stack[$position]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * {@inheritdoc}
     */
    abstract public function jsonSerialize();
}
