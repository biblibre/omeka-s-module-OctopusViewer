<?php

namespace MediaViewer\MediaRenderer;

use Laminas\View\HelperPluginManager;
use Laminas\View\Renderer\PhpRenderer;
use MediaViewer\FileRenderer\Manager as FileRendererManager;
use Omeka\Api\Representation\MediaRepresentation;

class File extends AbstractMediaRenderer
{
    protected $fileRendererManager;

    public function __construct(FileRendererManager $fileRendererManager)
    {
        $this->fileRendererManager = $fileRendererManager;
    }

    public function getJsDependencies(HelperPluginManager $viewHelpers): array
    {
        $jsDependencies = [];
        $fileRendererNames = $this->fileRendererManager->getRegisteredNames();
        foreach ($fileRendererNames as $fileRendererName) {
            $fileRenderer = $this->fileRendererManager->get($fileRendererName);
            $fileRendererJsDependencies = $fileRenderer->getJsDependencies($viewHelpers);
            $jsDependencies = array_merge($jsDependencies, $fileRendererJsDependencies);
        }

        return $jsDependencies;
    }

    public function render(PhpRenderer $view, MediaRepresentation $media): string
    {
        try {
            $fileRenderer = $this->fileRendererManager->get($media->mediaType());
        } catch (\Exception $e) {
            $fileRenderer = $this->fileRendererManager->get('fallback');
        }

        return $fileRenderer->render($view, $media);
    }
}
