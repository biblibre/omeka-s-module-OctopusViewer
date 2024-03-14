<?php

namespace OctopusViewer\FileRenderer;

use Laminas\View\HelperPluginManager;
use Laminas\View\Renderer\PhpRenderer;
use Omeka\Api\Representation\MediaRepresentation;

class Pdf extends AbstractFileRenderer
{
    public function getJsDependencies(HelperPluginManager $viewHelpers): array
    {
        $assetUrl = $viewHelpers->get('assetUrl');

        return [
            $assetUrl('js/octopusviewer-pdfjs.js', 'OctopusViewer'),
        ];
    }

    public function render(PhpRenderer $view, MediaRepresentation $media): string
    {
        $viewer_url = $view->serverUrl($view->url('octopusviewer-pdfjs-viewer'));

        $config = [
            'viewer_url' => $viewer_url,
        ];

        $values = [
            'config' => $config,
            'media' => $media,
        ];

        return $view->partial('octopus-viewer/partial/file-renderer/pdf', $values);
    }
}
