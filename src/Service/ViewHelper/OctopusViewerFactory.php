<?php

namespace OctopusViewer\Service\ViewHelper;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use OctopusViewer\View\Helper\OctopusViewer;

class OctopusViewerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $mediaRendererManager = $container->get('OctopusViewer\MediaRendererManager');

        return new OctopusViewer($mediaRendererManager);
    }
}
