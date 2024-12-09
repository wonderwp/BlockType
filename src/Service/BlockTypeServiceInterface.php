<?php

namespace WonderWp\Component\BlockType\Service;

use WonderWp\Component\BlockType\Definition\BlockTypeDefinition;
use WonderWp\Component\BlockType\Definition\BlockTypeInterface;
use WonderWp\Component\BlockType\Response\BlockTypeRegistrationResponse;
use WonderWp\Component\BlockType\Response\BlockTypeRegistrationResponseInterface;
use WonderWp\Component\PluginSkeleton\Service\RegistrableInterface;

interface BlockTypeServiceInterface extends RegistrableInterface
{
    /**
     * @return BlockTypeInterface[]
     */
    public function getBlockTypes(): array;

    /**
     * @param string $key
     * @return BlockTypeInterface|null
     */
    public function getBlockType(string $key): ?BlockTypeInterface;

    /**
     * @param BlockTypeInterface $BlockType
     * @return $this
     */
    public function addBlockType(BlockTypeInterface $BlockType): static;

    /**
     * @param string $key
     * @return $this
     */
    public function removeBlockType(string $key): static;

    /**
     * @param BlockTypeInterface[] $BlockTypes
     * @return $this
     */
    public function setBlockTypes(array $BlockTypes): static;

    /**
     * Register all BlockTypes
     *
     * @return BlockTypeRegistrationResponseInterface[]
     * @throws BlockTypeRegistrationException
     */
    public function registerBlockTypes(): array;

    /**
     * Register a BlockType
     *
     * @param BlockTypeInterface $BlockType
     * @return BlockTypeRegistrationResponseInterface
     * @throws BlockTypeRegistrationException
     */
    public function registerBlockType(BlockTypeInterface $BlockType): BlockTypeRegistrationResponseInterface;
}
