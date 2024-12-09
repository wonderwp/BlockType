<?php

namespace WonderWp\Component\BlockType\Service;

use WonderWp\Component\BlockType\Definition\BlockTypeInterface;
use WonderWp\Component\BlockType\Exception\BlockTypeRegistrationException;
use WonderWp\Component\BlockType\Response\BlockTypeRegistrationResponse;
use WonderWp\Component\BlockType\Response\BlockTypeRegistrationResponseInterface;
use WonderWp\Component\Service\AbstractService;

abstract class AbstractBlockTypeService extends AbstractService implements BlockTypeServiceInterface
{
    /** @var BlockTypeInterface[] */
    protected array $blockTypes = [];

    public function getBlockTypes(): array
    {
        return $this->blockTypes;
    }

    public function getBlockType(string $key): ?BlockTypeInterface
    {
        return $this->blockTypes[$key] ?? null;
    }

    public function addBlockType(BlockTypeInterface $BlockType): static
    {
        $this->blockTypes[$BlockType->getKey()] = $BlockType;

        return $this;
    }

    public function removeBlockType(string $key): static
    {
        if (isset($this->blockTypes[$key])) {
            unset($this->blockTypes[$key]);
        }

        return $this;
    }

    public function setBlockTypes(array $BlockTypes): static
    {
        $this->blockTypes = $BlockTypes;

        return $this;
    }

    //========================================================================================================//
    // Registration methods
    //========================================================================================================//

    public function registerBlockTypes(): array
    {
        $responses = [];

        foreach ($this->blockTypes as $blockType) {
            $responses[$blockType->getKey()] = $this->registerBlockType($blockType);
        }

        return $responses;
    }

    public function registerBlockType(BlockTypeInterface $blockType): BlockTypeRegistrationResponseInterface
    {
        try {
            $blocksOutputDir = $this->manager->getConfig('path.blocks.build');
            if (empty($blocksOutputDir)) {
                $blocksOutputDir = $this->manager->getConfig('path.root') . DIRECTORY_SEPARATOR . 'build';
                if (is_dir($blocksOutputDir . DIRECTORY_SEPARATOR . 'blocks')) {
                    $blocksOutputDir = $blocksOutputDir . DIRECTORY_SEPARATOR . 'blocks';
                }
            }

            $blocksOutputDir .= DIRECTORY_SEPARATOR . $blockType->getKey();

            $wpRes = \register_block_type($blocksOutputDir, $blockType->getArgs());

            if ($wpRes instanceof \WP_Error) {
                throw new BlockTypeRegistrationException($wpRes->get_error_message(), $wpRes->get_error_code());
            } else {
                $response = new BlockTypeRegistrationResponse(200, BlockTypeRegistrationResponseInterface::SUCCESS);
                $response->setWpRegistrationResult($wpRes);
            }
        } catch (\Exception $e) {
            $errorCode = is_int($e->getCode()) ? $e->getCode() : 500;
            $response = new BlockTypeRegistrationResponse($errorCode, BlockTypeRegistrationResponseInterface::ERROR);
            $response->setError($e);
        }

        return $response;
    }


}
