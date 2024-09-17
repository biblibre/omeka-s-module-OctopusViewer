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
        return ['items', 'media'];
    }

    public function render(PhpRenderer $view, AbstractResourceEntityRepresentation $resource) : string
    {
        if ($resource instanceof \Omeka\Api\Representation\ItemRepresentation) {
            return $view->octopusViewer()->forItem($resource);
        }

        if ($resource instanceof \Omeka\Api\Representation\MediaRepresentation) {
            return $view->octopusViewer()->forMedia($resource);
        }

        return '';
    }
}
