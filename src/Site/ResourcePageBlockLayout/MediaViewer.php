<?php
namespace MediaViewer\Site\ResourcePageBlockLayout;

use Laminas\View\Renderer\PhpRenderer;
use Omeka\Site\ResourcePageBlockLayout\ResourcePageBlockLayoutInterface;
use Omeka\Api\Representation\AbstractResourceEntityRepresentation;

class MediaViewer implements ResourcePageBlockLayoutInterface
{
    public function getLabel() : string
    {
        return 'Media viewer'; // @translate
    }

    public function getCompatibleResourceNames() : array
    {
        return ['items'];
    }

    public function render(PhpRenderer $view, AbstractResourceEntityRepresentation $resource) : string
    {
        return $view->mediaViewer()->forItem($resource);
    }
}
