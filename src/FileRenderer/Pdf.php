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
        $basePath = $viewHelpers->get('basePath');

        $pdfObjectUrl = $basePath() . '/modules/OctopusViewer/node_modules/pdfobject/pdfobject.min.js';

        return [
            $pdfObjectUrl,
            $assetUrl('js/octopusviewer-pdfobject.js', 'OctopusViewer'),
        ];
    }

    public function render(PhpRenderer $view, MediaRepresentation $media): string
    {
        $config = [
            'PDFJS_URL' => $view->serverUrl() . $view->basePath() . '/modules/OctopusViewer/vendor/mozilla/pdf.js/web/viewer.html',
        ];

        $values = [
            'config' => $config,
            'media' => $media,
        ];

        return $view->partial('octopus-viewer/partial/file-renderer/pdf', $values);
    }
}
