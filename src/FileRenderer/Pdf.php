<?php

namespace MediaViewer\FileRenderer;

use Laminas\View\Renderer\PhpRenderer;
use Omeka\Api\Representation\MediaRepresentation;

class Pdf implements FileRendererInterface
{
    public function preRender(PhpRenderer $view, MediaRepresentation $media): void
    {
        $pdfObjectUrl = $view->basePath . '/modules/MediaViewer/node_modules/pdfobject/pdfobject.min.js';
        $view->headScript()->appendFile($pdfObjectUrl);

        $view->headScript()->appendFile($view->assetUrl('js/mediaviewer-pdfobject.js', 'MediaViewer'));
    }

    public function render(PhpRenderer $view, MediaRepresentation $media): string
    {
        $config = [
            'PDFJS_URL' => $view->basePath . '/modules/MediaViewer/vendor/mozilla/pdf.js/web/viewer.html',
        ];

        $values = [
            'config' => $config,
            'media' => $media,
        ];

        return $view->partial('media-viewer/partial/file-renderer/pdf', $values);
    }
}
