<?php
namespace MediaViewer\Service\Controller\Site;

use Interop\Container\ContainerInterface;
use MediaViewer\Controller\Site\ViewerController;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ViewerControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $services, $requestedName, array $options = null)
    {
        $mediaRendererManager = $services->get('MediaViewer\MediaRendererManager');

        return new ViewerController($mediaRendererManager);
    }
}
