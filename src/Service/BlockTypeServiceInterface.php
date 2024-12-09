<?php

namespace WonderWp\Component\BlockType\Service;

use WonderWp\Component\BlockType\Definition\BlockTypeDefinition;
use WonderWp\Component\BlockType\Response\BlockTypeRegistrationResponse;

interface BlockTypeServiceInterface
{
    /**
     * @param BlockTypeDefinition $definition
     * @return BlockTypeRegistrationResponse
     */
    public function register(BlockTypeDefinition $definition);

    /**
     * @param string $blockName
     * @return BlockTypeRegistrationResponse
     */
    public function unregister($blockName);
} 