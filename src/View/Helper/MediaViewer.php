<?php

namespace MediaViewer\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use MediaViewer\MediaRenderer\Manager as MediaRendererManager;
use Omeka\Api\Representation\ItemRepresentation;
use Omeka\Api\Representation\MediaRepresentation;

class MediaViewer extends AbstractHelper
{
    protected $mediaRendererManager;

    public function __construct(MediaRendererManager $mediaRendererManager)
    {
        $this->mediaRendererManager = $mediaRendererManager;
    }

    public function viewer(array $query)
    {
        $view = $this->getView();

        $view->headScript()->appendFile($view->assetUrl('js/mediaviewer-viewer.js', 'MediaViewer'));

        $args = [
            'query' => $query,
        ];

        return $view->partial('media-viewer/helper/mediaviewer/viewer', $args);
    }

    public function forItem(ItemRepresentation $item)
    {
        $view = $this->getView();

        $view->headLink()->appendStylesheet($view->assetUrl('css/mediaviewer.css', 'MediaViewer'));
        $view->headScript()->appendFile($view->assetUrl('js/mediaviewer-viewer.js', 'MediaViewer'));

        $args = [
            'item' => $item,
        ];

        return $view->partial('media-viewer/helper/mediaviewer/for-item', $args);
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
