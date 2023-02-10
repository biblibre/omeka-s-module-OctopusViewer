<?php

namespace MediaViewer\FileRenderer;

use Laminas\View\HelperPluginManager;
use Laminas\View\Renderer\PhpRenderer;
use Omeka\Api\Representation\MediaRepresentation;

class Pdf extends AbstractFileRenderer
{
    public function getJsDependencies(HelperPluginManager $viewHelpers): array
    {
        $assetUrl = $viewHelpers->get('assetUrl');
        $basePath = $viewHelpers->get('basePath');

        $pdfObjectUrl = $basePath() . '/modules/MediaViewer/node_modules/pdfobject/pdfobject.min.js';

        return [
            $pdfObjectUrl,
            $assetUrl('js/mediaviewer-pdfobject.js', 'MediaViewer'),
        ];
    }

    public function render(PhpRenderer $view, MediaRepresentation $media): string
    {
        $config = [
            'PDFJS_URL' => $view->serverUrl() . $view->basePath() . '/modules/MediaViewer/vendor/mozilla/pdf.js/web/viewer.html',
        ];

        $values = [
            'config' => $config,
            'media' => $media,
        ];

        return $view->partial('media-viewer/partial/file-renderer/pdf', $values);
    }
}
