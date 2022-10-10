<?php

namespace MediaViewer\Service;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use MediaViewer\MediaRenderer;

class MediaRendererFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $mediaRendererManager = $container->get('MediaViewer\MediaRendererManager');

        return new MediaRenderer($mediaRendererManager);
    }
}
