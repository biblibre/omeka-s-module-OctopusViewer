<?php

namespace MediaViewer\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use MediaViewer\MediaRenderer\Manager as MediaRendererManager;
use Omeka\Api\Representation\ItemRepresentation;
use Omeka\Api\Representation\MediaRepresentation;

class MediaViewer extends AbstractHelper
{
    const PARTIAL_NAME = 'media-viewer/common/mediaviewer';

    protected $mediaRendererManager;

    public function __construct(MediaRendererManager $mediaRendererManager)
    {
        $this->mediaRendererManager = $mediaRendererManager;
    }

    public function forItem(ItemRepresentation $item)
    {
        $view = $this->getView();

        foreach ($item->media() as $media) {
            try {
                $mediaRenderer = $this->mediaRendererManager->get($media->renderer());
            } catch (\Exception $e) {
                $mediaRenderer = $this->mediaRendererManager->get('fallback');
            }
            $mediaRenderer->preRender($view, $media);
        }

        $view->headScript()->appendFile($view->assetUrl('js/mediaviewer.js', 'MediaViewer'));
        $view->headLink()->appendStylesheet($view->assetUrl('css/mediaviewer.css', 'MediaViewer'));

        $args = [
            'item' => $item,
        ];

        return $view->partial(self::PARTIAL_NAME, $args);
    }

    public function renderMedia(MediaRepresentation $media)
    {
        try {
            $mediaRenderer = $this->mediaRendererManager->get($media->renderer());
        } catch (\Exception $e) {
            $mediaRenderer = $this->mediaRendererManager->get('fallback');
        }

        return $mediaRenderer->render($this->getView(), $media);
    }
}
