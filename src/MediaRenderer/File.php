<?php

namespace MediaViewer\MediaRenderer;

use Laminas\View\Renderer\PhpRenderer;
use MediaViewer\FileRenderer\Manager as FileRendererManager;
use Omeka\Api\Representation\MediaRepresentation;

class File implements MediaRendererInterface
{
    protected $fileRendererManager;

    public function __construct(FileRendererManager $fileRendererManager)
    {
        $this->fileRendererManager = $fileRendererManager;
    }

    public function preRender(PhpRenderer $view, MediaRepresentation $media): void
    {
        try {
            $fileRenderer = $this->fileRendererManager->get($media->mediaType());
        } catch (\Exception $e) {
            $fileRenderer = $this->fileRendererManager->get('fallback');
        }

        $fileRenderer->preRender($view, $media);
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
