<?php

namespace MediaViewer\MediaRenderer;

use Laminas\View\Renderer\PhpRenderer;
use Omeka\Api\Representation\MediaRepresentation;

class Fallback implements MediaRendererInterface
{
    public function preRender(PhpRenderer $view, MediaRepresentation $media): void
    {
    }

    public function render(PhpRenderer $view, MediaRepresentation $media): string
    {
        return $media->render();
    }
}
