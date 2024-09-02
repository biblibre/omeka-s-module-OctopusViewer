<?php
namespace OctopusViewer\Service\Controller\Site;

use Interop\Container\ContainerInterface;
use OctopusViewer\Controller\Site\MediaController;
use Laminas\ServiceManager\Factory\FactoryInterface;

class MediaControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $services, $requestedName, array $options = null)
    {
        $fileStore = $services->get('Omeka\File\Store');

        return new MediaController($fileStore);
    }
}
