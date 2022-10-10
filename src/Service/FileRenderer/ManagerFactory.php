<?php

namespace MediaViewer\Service\FileRenderer;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use MediaViewer\FileRenderer\Manager as FileRendererManager;
use Omeka\Service\Exception\ConfigException;

class ManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        if (!isset($config['mediaviewer_file_renderers'])) {
            throw new ConfigException('Missing MediaViewer file renderer configuration');
        }

        return new FileRendererManager($container, $config['mediaviewer_file_renderers']);
    }
}
