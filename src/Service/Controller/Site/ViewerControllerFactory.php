<?php
namespace OctopusViewer\Service\Controller\Site;

use Interop\Container\ContainerInterface;
use OctopusViewer\Controller\Site\ViewerController;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ViewerControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $services, $requestedName, array $options = null)
    {
        $mediaRendererManager = $services->get('OctopusViewer\MediaRendererManager');

        return new ViewerController($mediaRendererManager);
    }
}
