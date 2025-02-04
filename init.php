<?php

use WonderWp\Component\BlockType\Service\BlockTypeService;
use WonderWp\Component\BlockType\Service\BlockTypeServiceInterface;
use WonderWp\Component\PluginSkeleton\Exception\ServiceNotFoundException;
use WonderWp\Component\PluginSkeleton\ManagerAwareInterface;
use WonderWp\Component\Service\ServiceInterface;
use WonderWp\Component\PluginSkeleton\ManagerInterface;
use WonderWp\Component\DependencyInjection\Container;

add_action('wonderwp.loader.load', 'wwp_register_blocktype_definitions_towards_container', 10, 2);
add_action('wwp.abstract_manager.run', 'wwp_register_blocktype_service_towards_manager', 10, 2);

function wwp_register_blocktype_definitions_towards_container(Container $container)
{
    /**
     * Block Types
     */
    $container['wwp.blockType.defaultService'] = $container->factory(function () {
        return new BlockTypeService();
    });
}

function wwp_register_blocktype_service_towards_manager(ManagerInterface $manager, Container $container)
{
    //Block Types
    try {
        $blockTypeService = $manager->getService(ServiceInterface::BLOCK_TYPE_SERVICE_NAME);
        if ($blockTypeService instanceof BlockTypeServiceInterface) {
            $blockTypeService->register();
        }
    } catch (ServiceNotFoundException $e) {
        if ($e->getServiceType() === ServiceInterface::BLOCK_TYPE_SERVICE_NAME) {
            //No block type service found, use the default one instead
            $blockTypeService = $container['wwp.blockType.defaultService'];
            if ($blockTypeService instanceof BlockTypeServiceInterface) {
                if ($blockTypeService instanceof ManagerAwareInterface) {
                    $blockTypeService->setManager($manager);
                }
                $blockTypeService->register();
            }
        } else {
            throw $e;
        }
    }
}
