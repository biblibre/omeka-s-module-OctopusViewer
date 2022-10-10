<?php

namespace MediaViewer\MediaRenderer;

use Laminas\View\Renderer\PhpRenderer;
use Omeka\Api\Representation\MediaRepresentation;

class Iiif implements MediaRendererInterface
{
    public function preRender(PhpRenderer $view, MediaRepresentation $media): void
    {
        $view->headScript()->appendFile($view->assetUrl('vendor/openseadragon/openseadragon.min.js', 'Omeka'));
        $view->headScript()->appendFile($view->assetUrl('js/mediaviewer-openseadragon.js', 'MediaViewer'));
    }

    public function render(PhpRenderer $view, MediaRepresentation $media): string
    {
        $config = [
            'prefixUrl' => $view->assetUrl('vendor/openseadragon/images/', 'Omeka', false, false),
            'showRotationControl' => true,
            'showFlipControl' => true,
            'showFullPageControl' => false,
            'animationTime' => 0.2,
            'tileSources' => $media->source(),
        ];

        $values = [
            'config' => $config,
        ];

        return $view->partial('media-viewer/partial/media-renderer/iiif', $values);
    }
}
