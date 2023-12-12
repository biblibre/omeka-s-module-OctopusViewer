<?php
namespace OctopusViewer\Site\ResourcePageBlockLayout;

use Laminas\View\Renderer\PhpRenderer;
use Omeka\Site\ResourcePageBlockLayout\ResourcePageBlockLayoutInterface;
use Omeka\Api\Representation\AbstractResourceEntityRepresentation;

class OctopusViewer implements ResourcePageBlockLayoutInterface
{
    public function getLabel() : string
    {
        return 'Octopus viewer'; // @translate
    }

    public function getCompatibleResourceNames() : array
    {
        return ['items'];
    }

    public function render(PhpRenderer $view, AbstractResourceEntityRepresentation $resource) : string
    {
        return $view->octopusViewer()->forItem($resource);
    }
}
