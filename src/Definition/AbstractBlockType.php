<?php

namespace WonderWp\Component\BlockType;

abstract class AbstractBlockType implements BlockTypeInterface
{
    /** @var string */
    protected string $name;

    /** @var array */
    protected array $args = [];

    /**
     * @param string $name
     * @param array $args
     */
    public function __construct(string $name, array $args = [])
    {
        $this->name = $name;
        $this->args = $args;
    }

    /** @inerhitDoc */
    public function getName(): string
    {
        return $this->name;
    }

    /** @inerhitDoc */
    public function setName(string $name): AbstractBlockType
    {
        $this->name = $name;
        return $this;
    }

    /** @inerhitDoc */
    public function getArgs(): array
    {
        return $this->args;
    }

    /** @inerhitDoc */
    public function setArgs(array $args): AbstractBlockType
    {
        $this->args = $args;
        return $this;
    }
}
