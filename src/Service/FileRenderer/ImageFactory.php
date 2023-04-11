<?php

namespace OctopusViewer\Service\FileRenderer;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use OctopusViewer\FileRenderer\Image;

class ImageFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $settings = $container->get('Omeka\Settings');

        return new Image($settings);
    }
}
