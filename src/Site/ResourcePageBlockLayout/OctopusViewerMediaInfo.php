<?php
namespace OctopusViewer\Site\ResourcePageBlockLayout;

use Laminas\View\Renderer\PhpRenderer;
use Omeka\Site\ResourcePageBlockLayout\ResourcePageBlockLayoutInterface;
use Omeka\Api\Representation\AbstractResourceEntityRepresentation;
use Omeka\Api\Representation\ItemRepresentation;

class OctopusViewerMediaInfo implements ResourcePageBlockLayoutInterface
{
    public function getLabel() : string
    {
        return 'Octopus viewer media metadata'; // @translate
    }

    public function getCompatibleResourceNames() : array
    {
        return ['items', 'media'];
    }

    public function render(PhpRenderer $view, AbstractResourceEntityRepresentation $resource) : string
    {
        if ($resource instanceof ItemRepresentation) {
            $item = $resource;
            $media = $item->primaryMedia();
            if (!$media) {
                return '';
            }

            $attributes = [
                'media-query' => http_build_query(['item_id' => $item->id()]),
                'media-id' => $media->id(),
            ];
        } else {
            $media = $resource;
            $attributes = [
                'media-query' => http_build_query(['id' => $media->id()]),
                'media-id' => $media->id(),
            ];
        }

        return $view->octopusViewer()->mediaInfo($attributes);
    }
}
