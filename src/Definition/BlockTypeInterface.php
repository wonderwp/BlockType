<?php

namespace WonderWp\Component\BlockType;

interface BlockTypeInterface
{

    /**
     * @return string
     * @see https://developer.wordpress.org/reference/functions/register_block_type/
     */
    public function getKey(): string;

    /**
     * @param string $key
     * @return BlockTypeInterface
     * @see https://developer.wordpress.org/reference/functions/register_block_type/
     */
    public function setKey(string $key): static;

    /**
     * @return array
     * @see https://developer.wordpress.org/reference/functions/register_block_type/
     */
    public function getArgs(): array;

    /**
     * @param array $args
     * @return $this
     * @see https://developer.wordpress.org/reference/functions/register_block_type/
     */
    public function setArgs(array $args): static;
}
