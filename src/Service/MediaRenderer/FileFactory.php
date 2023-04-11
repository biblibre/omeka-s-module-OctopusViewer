<?php

namespace OctopusViewer\Service\MediaRenderer;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use OctopusViewer\MediaRenderer\File;

class FileFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $fileRendererManager = $container->get('OctopusViewer\FileRendererManager');

        return new File($fileRendererManager);
    }
}
