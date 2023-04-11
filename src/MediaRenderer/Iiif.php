<?php

namespace OctopusViewer\MediaRenderer;

use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\HelperPluginManager;
use Omeka\Api\Representation\MediaRepresentation;

class Iiif extends AbstractMediaRenderer
{
    public function getJsDependencies(HelperPluginManager $viewHelpers): array
    {
        $assetUrl = $viewHelpers->get('assetUrl');

        return [
            $assetUrl('vendor/openseadragon/openseadragon.min.js', 'Omeka'),
            $assetUrl('js/octopusviewer-openseadragon.js', 'OctopusViewer'),
        ];
    }

    public function render(PhpRenderer $view, MediaRepresentation $media): string
    {
        $config = [
            'prefixUrl' => $view->assetUrl('vendor/openseadragon/images/', 'Omeka', false, false, true),
            'showRotationControl' => true,
            'showFlipControl' => true,
            'showFullPageControl' => false,
            'animationTime' => 0.2,
            'tileSources' => $media->source(),
        ];

        $values = [
            'config' => $config,
        ];

        return $view->partial('octopus-viewer/partial/media-renderer/iiif', $values);
    }
}
