<?php

namespace OctopusViewer\Service;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use OctopusViewer\MediaRenderer;

class MediaRendererFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $mediaRendererManager = $container->get('OctopusViewer\MediaRendererManager');

        return new MediaRenderer($mediaRendererManager);
    }
}
