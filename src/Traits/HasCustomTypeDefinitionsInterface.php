<?php

namespace WonderWp\Component\BlockType\Traits;

use WonderWp\Component\BlockType\BlockTypeInterface;

interface HasCustomTypeDefinitionsInterface
{
    /**
     * Provide the key of the custom post type
     * @see BlockTypeInterface::setKey()
     * @return string
     */
    public static function provideKey(): string;

    /**
     * Provide the args of the custom post type
     * @see BlockTypeInterface::setArgs()
     * @return array
     */
    public static function provideArgs(): array;
}
