<?php
namespace OctopusViewer\Site\ResourcePageBlockLayout;

use Laminas\View\Renderer\PhpRenderer;
use Omeka\Site\ResourcePageBlockLayout\ResourcePageBlockLayoutInterface;
use Omeka\Api\Representation\AbstractResourceEntityRepresentation;

class OctopusViewerMediaSelector implements ResourcePageBlockLayoutInterface
{
    public function getLabel() : string
    {
        return 'Octopus viewer media selector'; // @translate
    }

    public function getCompatibleResourceNames() : array
    {
        return ['items'];
    }

    public function render(PhpRenderer $view, AbstractResourceEntityRepresentation $resource) : string
    {
        $media = $resource->primaryMedia();
        if (!$media) {
            return '';
        }

        $attributes = [
            'media-query' => http_build_query(['item_id' => $resource->id()]),
            'media-id' => $media->id(),
        ];

        return $view->octopusViewer()->mediaSelector($attributes);
    }
}
