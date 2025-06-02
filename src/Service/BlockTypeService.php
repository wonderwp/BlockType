<?php

namespace WonderWp\Component\BlockType\Service;

use WonderWp\Component\BlockType\Definition\BlockTypeInterface;
use WonderWp\Component\BlockType\Exception\BlockTypeRegistrationException;
use WonderWp\Component\BlockType\Response\BlockTypeRegistrationResponse;
use WonderWp\Component\PluginSkeleton\ManagerAwareTrait;

class BlockTypeService extends AbstractBlockTypeService
{
    use ManagerAwareTrait;

    public function register()
    {
        add_action('init', function(){
            $autoLoaded = $this->autoload();
        },9);

        add_action('init', [$this, 'registerBlockTypesFromManifest']);
    }

    public function autoload(array $classNameFromFiles = [], array $discoveryPaths = [], callable $successCallback = null, array $excludedClasses=[]): array
    {
        $discoveryPathsRoots = $this->manager->getConfig('discoveryPathsRoots', [
            'block-types' => rtrim($this->manager->getConfig('path.root') ?? '', DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR
        ]);
        $discoverFolderSuffix = $this->manager->getConfig('blockTypeService.discoverFolderSuffix', 'BlockTypes');
        $defaultPaths = $this->deductDefaultDiscoveryPaths($discoveryPathsRoots, $discoverFolderSuffix);
        $discoveryPaths = array_merge($defaultPaths, $discoveryPaths);

        $autoLoaded = parent::autoload($classNameFromFiles, $discoveryPaths, $successCallback);

        if (!empty($this->blockTypes)) {
            $this->registerBlockTypes();
        }

        return $autoLoaded;
    }

    protected function autoloadFile(string $className, string $filePath): object
    {
        $instance = parent::autoloadFile($className, $filePath);

        if($instance instanceof BlockTypeInterface) {
            $this->addBlockType($instance);
        }

        return $instance;
    }

    public function registerBlockTypesFromManifest()
    {
        $manifestFilePath = $this->manager->getConfig('blocks.manifest.path');
        if(empty($manifestFilePath) || !file_exists($manifestFilePath)) {
            return;
        }

        $blocksOutputDir =  $this->getBlocksOutputDir();
        if(empty($blocksOutputDir) || !is_dir($blocksOutputDir)) {
            return;
        }

        /**
         * Registers the block(s) metadata from the `blocks-manifest.php` and registers the block type(s)
         * based on the registered block metadata.
         * Added in WordPress 6.8 to simplify the block metadata registration process added in WordPress 6.7.
         *
         * @see https://make.wordpress.org/core/2025/03/13/more-efficient-block-type-registration-in-6-8/
         */
        if ( function_exists( 'wp_register_block_types_from_metadata_collection' ) ) {
            wp_register_block_types_from_metadata_collection( $blocksOutputDir, $manifestFilePath );
            return;
        }

        /**
         * Registers the block(s) metadata from the `blocks-manifest.php` file.
         * Added to WordPress 6.7 to improve the performance of block type registration.
         *
         * @see https://make.wordpress.org/core/2024/10/17/new-block-type-registration-apis-to-improve-performance-in-wordpress-6-7/
         */
        if ( function_exists( 'wp_register_block_metadata_collection' ) ) {
            wp_register_block_metadata_collection( $blocksOutputDir, $manifestFilePath );
        }
        /**
         * Registers the block type(s) in the `blocks-manifest.php` file.
         *
         * @see https://developer.wordpress.org/reference/functions/register_block_type/
         */
        $manifest_data = require __DIR__ . '/build/blocks/blocks-manifest.php';
        foreach ( array_keys( $manifest_data ) as $block_type ) {
            register_block_type( dirname($blocksOutputDir) . "/{$block_type}" );
        }
    }
}
