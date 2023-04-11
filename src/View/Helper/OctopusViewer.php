<?php

namespace OctopusViewer\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use OctopusViewer\MediaRenderer\Manager as MediaRendererManager;
use Omeka\Api\Representation\ItemRepresentation;
use Omeka\Api\Representation\MediaRepresentation;

class OctopusViewer extends AbstractHelper
{
    protected $mediaRendererManager;

    public function __construct(MediaRendererManager $mediaRendererManager)
    {
        $this->mediaRendererManager = $mediaRendererManager;
    }

    public function viewer(array $query)
    {
        $view = $this->getView();

        $view->headScript()->appendFile($view->assetUrl('js/octopusviewer-viewer.js', 'OctopusViewer'));

        $args = [
            'query' => $query,
        ];

        return $view->partial('octopus-viewer/helper/octopusviewer/viewer', $args);
    }

    public function forItem(ItemRepresentation $item)
    {
        $media = $item->media();
        if (empty($media)) {
            return '';
        }

        $view = $this->getView();

        $view->headLink()->appendStylesheet($view->assetUrl('css/octopusviewer.css', 'OctopusViewer'));
        $view->headScript()->appendFile($view->assetUrl('js/octopusviewer-viewer.js', 'OctopusViewer'));

        $args = [
            'item' => $item,
        ];

        return $view->partial('octopus-viewer/helper/octopusviewer/for-item', $args);
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
