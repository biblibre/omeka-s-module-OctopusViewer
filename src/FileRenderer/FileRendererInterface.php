<?php

namespace MediaViewer\FileRenderer;

use Laminas\View\HelperPluginManager;
use Laminas\View\Renderer\PhpRenderer;
use Omeka\Api\Representation\MediaRepresentation;

interface FileRendererInterface
{
    public function getJsDependencies(HelperPluginManager $viewHelpers): array;
    public function render(PhpRenderer $view, MediaRepresentation $media): string;
}
