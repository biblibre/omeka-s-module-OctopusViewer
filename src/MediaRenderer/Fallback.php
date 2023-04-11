<?php

namespace OctopusViewer\MediaRenderer;

use Laminas\View\Renderer\PhpRenderer;
use Omeka\Api\Representation\MediaRepresentation;

class Fallback extends AbstractMediaRenderer
{
    public function render(PhpRenderer $view, MediaRepresentation $media): string
    {
        return $media->render();
    }
}
