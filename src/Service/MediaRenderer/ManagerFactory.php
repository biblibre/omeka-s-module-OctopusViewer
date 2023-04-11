<?php

namespace OctopusViewer\Service\MediaRenderer;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use OctopusViewer\MediaRenderer\Manager as MediaRendererManager;
use Omeka\Service\Exception\ConfigException;

class ManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        if (!isset($config['octopusviewer_media_renderers'])) {
            throw new ConfigException('Missing OctopusViewer media renderer configuration');
        }

        return new MediaRendererManager($container, $config['octopusviewer_media_renderers']);
    }
}
