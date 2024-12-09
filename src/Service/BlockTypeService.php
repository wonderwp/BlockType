<?php

namespace WonderWp\Component\BlockType\Service;

use WonderWp\Component\BlockType\BlockTypeInterface;
use WonderWp\Component\BlockType\Exception\BlockTypeRegistrationException;
use WonderWp\Component\BlockType\Response\BlockTypeRegistrationResponse;

class BlockTypeService implements BlockTypeServiceInterface
{
    /**
     * @param BlockTypeDefinition $definition
     * @return BlockTypeRegistrationResponse
     * @throws BlockTypeRegistrationException
     */
    public function register(BlockTypeInterface $definition)
    {
        try {
            if (!function_exists('register_block_type')) {
                throw new BlockTypeRegistrationException('Function register_block_type does not exist');
            }

            $result = register_block_type($definition->getName(), $definition->getArgs());

            if (!$result) {
                throw new BlockTypeRegistrationException('Failed to register block type');
            }

            return new BlockTypeRegistrationResponse(true, 'Block type registered successfully', $result);
        } catch (\Exception $e) {
            throw new BlockTypeRegistrationException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param string $blockName
     * @return BlockTypeRegistrationResponse
     */
    public function unregister($blockName)
    {
        if (function_exists('unregister_block_type')) {
            $result = unregister_block_type($blockName);
            return new BlockTypeRegistrationResponse(
                $result !== false,
                $result !== false ? 'Block type unregistered successfully' : 'Failed to unregister block type'
            );
        }

        return new BlockTypeRegistrationResponse(false, 'Function unregister_block_type does not exist');
    }
}
