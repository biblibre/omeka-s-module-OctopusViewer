<?php

namespace MediaViewer\FileRenderer;

use Laminas\View\Renderer\PhpRenderer;
use Omeka\Api\Representation\MediaRepresentation;

class Fallback implements FileRendererInterface
{
    public function preRender(PhpRenderer $view, MediaRepresentation $media): void
    {
    }

    public function render(PhpRenderer $view, MediaRepresentation $media): string
    {
        return $media->render();
    }
}
