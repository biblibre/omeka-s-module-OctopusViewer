<?php

namespace OctopusViewer\Service\FileRenderer;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use OctopusViewer\FileRenderer\Manager as FileRendererManager;
use Omeka\Service\Exception\ConfigException;

class ManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        if (!isset($config['octopusviewer_file_renderers'])) {
            throw new ConfigException('Missing OctopusViewer file renderer configuration');
        }

        return new FileRendererManager($container, $config['octopusviewer_file_renderers']);
    }
}
