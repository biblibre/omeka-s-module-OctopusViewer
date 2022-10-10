<?php

namespace MediaViewer\Service\MediaRenderer;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use MediaViewer\MediaRenderer\File;

class FileFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $fileRendererManager = $container->get('MediaViewer\FileRendererManager');

        return new File($fileRendererManager);
    }
}
