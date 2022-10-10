<?php

namespace MediaViewer\Service\MediaRenderer;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use MediaViewer\MediaRenderer\Manager as MediaRendererManager;
use Omeka\Service\Exception\ConfigException;

class ManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        if (!isset($config['mediaviewer_media_renderers'])) {
            throw new ConfigException('Missing MediaViewer media renderer configuration');
        }

        return new MediaRendererManager($container, $config['mediaviewer_media_renderers']);
    }
}
