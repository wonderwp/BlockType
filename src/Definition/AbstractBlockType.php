<?php

namespace WonderWp\Component\BlockType\Definition;

abstract class AbstractBlockType implements BlockTypeInterface
{
    /** @var string */
    protected string $key;

    /** @var array */
    protected array $args = [];

    /**
     * @param string $key
     * @param array $args
     */
    public function __construct(string $key, array $args = [])
    {
        $this->key = $key;
        $this->args = $args;
    }

    /** @inerhitDoc */
    public function getKey(): string
    {
        return $this->key;
    }

    /** @inerhitDoc */
    public function setKey(string $key): static
    {
        $this->key = $key;
        return $this;
    }

    /** @inerhitDoc */
    public function getArgs(): array
    {
        return $this->args;
    }

    /** @inerhitDoc */
    public function setArgs(array $args): static
    {
        $this->args = $args;
        return $this;
    }
}
