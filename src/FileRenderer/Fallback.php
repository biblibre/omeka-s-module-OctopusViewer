<?php

namespace OctopusViewer\FileRenderer;

use Laminas\View\Renderer\PhpRenderer;
use Omeka\Api\Representation\MediaRepresentation;

class Fallback extends AbstractFileRenderer
{
    public function render(PhpRenderer $view, MediaRepresentation $media): string
    {
        return $media->render();
    }
}
