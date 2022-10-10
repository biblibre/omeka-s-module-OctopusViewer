<?php

namespace MediaViewer\Service\ViewHelper;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use MediaViewer\View\Helper\MediaViewer;

class MediaViewerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $mediaRendererManager = $container->get('MediaViewer\MediaRendererManager');

        return new MediaViewer($mediaRendererManager);
    }
}
