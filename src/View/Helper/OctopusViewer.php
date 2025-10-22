<?php

namespace OctopusViewer\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use OctopusViewer\MediaRenderer\Manager as MediaRendererManager;
use Omeka\Api\Representation\ItemRepresentation;
use Omeka\Api\Representation\MediaRepresentation;
use Omeka\Api\Representation\AbstractResourceEntityRepresentation;

class OctopusViewer extends AbstractHelper
{
    protected $mediaRendererManager;

    public function __construct(MediaRendererManager $mediaRendererManager)
    {
        $this->mediaRendererManager = $mediaRendererManager;
    }

    public function viewer(array $query, string $title = '')
    {
        $view = $this->getView();

        $view->headScript()->appendFile($view->assetUrl('js/octopusviewer-viewer.js', 'OctopusViewer'));

        $args = [
            'query' => $query,
            'title' => $title,
            'extraStylesheet' => $view->assetUrl('css/octopusviewer-viewer-extra.css', 'OctopusViewer', $override = true),
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
        $view->headScript()->appendFile($view->assetUrl('js/octopusviewer-controller.js', 'OctopusViewer'));
        $view->headScript()->appendFile($view->assetUrl('js/octopusviewer-viewer.js', 'OctopusViewer'));

        $args = [
            'item' => $item,
        ];

        return $view->partial('octopus-viewer/helper/octopusviewer/for-item', $args);
    }

    public function forMedia(MediaRepresentation $media)
    {
        $view = $this->getView();

        $view->headLink()->appendStylesheet($view->assetUrl('css/octopusviewer.css', 'OctopusViewer'));
        $view->headScript()->appendFile($view->assetUrl('js/octopusviewer-controller.js', 'OctopusViewer'));
        $view->headScript()->appendFile($view->assetUrl('js/octopusviewer-viewer.js', 'OctopusViewer'));

        $args = [
            'media' => $media,
        ];

        return $view->partial('octopus-viewer/helper/octopusviewer/for-media', $args);
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

    public function mediaSelector(ItemRepresentation $item)
    {
        $media = $item->media();
        if (empty($media)) {
            return '';
        }

        $view = $this->getView();

        $view->headLink()->appendStylesheet($view->assetUrl('css/octopusviewer.css', 'OctopusViewer'));
        $view->headLink()->appendStylesheet($view->assetUrl('css/octopusviewer-viewer.css', 'OctopusViewer'));
        $view->headScript()->appendFile($view->assetUrl('js/octopusviewer-controller.js', 'OctopusViewer'));
        $view->headScript()->appendFile($view->assetUrl('js/octopusviewer-media-selector.js', 'OctopusViewer'));

        $args = [
            'item' => $item,
        ];

        return $view->partial('octopus-viewer/helper/octopusviewer/media-selector', $args);
    }

    public function mediaView(AbstractResourceEntityRepresentation $resource)
    {
        $view = $this->getView();

        $view->headLink()->appendStylesheet($view->assetUrl('css/octopusviewer.css', 'OctopusViewer'));
        $view->headLink()->appendStylesheet($view->assetUrl('css/octopusviewer-viewer.css', 'OctopusViewer'));
        $view->headScript()->appendFile($view->assetUrl('js/octopusviewer-controller.js', 'OctopusViewer'));
        $view->headScript()->appendFile($view->assetUrl('js/octopusviewer-media-view.js', 'OctopusViewer'));

        $args = [
            'resource' => $resource,
        ];

        return $view->partial('octopus-viewer/helper/octopusviewer/media-view', $args);
    }

    public function mediaInfo(AbstractResourceEntityRepresentation $resource)
    {
        $view = $this->getView();

        $view->headLink()->appendStylesheet($view->assetUrl('css/octopusviewer.css', 'OctopusViewer'));
        $view->headLink()->appendStylesheet($view->assetUrl('css/octopusviewer-viewer.css', 'OctopusViewer'));
        $view->headScript()->appendFile($view->assetUrl('js/octopusviewer-controller.js', 'OctopusViewer'));
        $view->headScript()->appendFile($view->assetUrl('js/octopusviewer-media-info.js', 'OctopusViewer'));

        $args = [
            'resource' => $resource,
        ];

        return $view->partial('octopus-viewer/helper/octopusviewer/media-info', $args);
    }
}
