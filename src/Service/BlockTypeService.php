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
    }

    public function autoload(array $classNameFromFiles = [], array $discoveryPaths = [], callable $successCallback = null, array $excludedClasses=[]): array
    {
        $discoveryPathsRoots = $this->manager->getConfig('discoveryPathsRoots', [
            'block-types' => rtrim($this->manager->getConfig('path.root'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR
        ]);
        $discoverFolderSuffix = $this->manager->getConfig('cptservice.discoverFolderSuffix', 'BlockTypes');
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

        $this->addBlockType($instance);

        return $instance;
    }

}
