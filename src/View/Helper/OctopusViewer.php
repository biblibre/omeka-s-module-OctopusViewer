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

    public function viewer(array $query, string $title = '', array $attributes = [])
    {
        $view = $this->getView();

        $attributes['instance-key'] ??= bin2hex(random_bytes(16));
        $attributes['media-query'] ??= http_build_query($query);
        $attributes['site-slug'] ??= $view->layout()->site->slug();
        $attributes['show-media-selector'] ??= $view->siteSetting('octopusviewer_show_media_selector', '') ?: $view->setting('octopusviewer_show_media_selector', 'auto');
        $attributes['show-media-info'] ??= $view->siteSetting('octopusviewer_show_media_info', '') ?: $view->setting('octopusviewer_show_media_info', 'always');
        $attributes['show-download-link'] ??= $view->siteSetting('octopusviewer_show_download_link', '') ?: $view->setting('octopusviewer_show_download_link', 'no');
        $attributes['extra-stylesheet'] ??= $view->assetUrl('css/octopusviewer-viewer-extra.css', 'OctopusViewer', $override = true);

        $args = [
            'query' => $query,
            'title' => $title,
            'attributes' => $attributes,
        ];

        return $view->partial('octopus-viewer/helper/octopusviewer/viewer', $args);
    }

    public function forItem(ItemRepresentation $item)
    {
        $media = $item->primaryMedia();
        if (!$media) {
            return '';
        }

        $view = $this->getView();

        $args = [
            'item' => $item,
        ];

        return $view->partial('octopus-viewer/helper/octopusviewer/for-item', $args);
    }

    public function forMedia(MediaRepresentation $media)
    {
        $view = $this->getView();

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

    public function mediaSelector(array $attributes = [])
    {
        $view = $this->getView();

        $attributes['site-slug'] ??= $view->layout()->site->slug();
        $attributes['show-media-selector'] ??= $view->siteSetting('octopusviewer_show_media_selector_block', '') ?: $view->setting('octopusviewer_show_media_selector_block', 'auto');

        $args = [
            'attributes' => $attributes,
        ];

        return $view->partial('octopus-viewer/helper/octopusviewer/media-selector', $args);
    }

    public function mediaView(array $attributes = [])
    {
        $view = $this->getView();

        $attributes['site-slug'] ??= $view->layout()->site->slug();
        $attributes['show-media-selector'] ??= $view->siteSetting('octopusviewer_show_media_selector', '') ?: $view->setting('octopusviewer_show_media_selector', 'auto');
        $attributes['show-media-info'] ??= $view->siteSetting('octopusviewer_show_media_info', '') ?: $view->setting('octopusviewer_show_media_info', 'always');
        $attributes['show-download-link'] ??= $view->siteSetting('octopusviewer_show_download_link', '') ?: $view->setting('octopusviewer_show_download_link', 'no');
        $attributes['extra-stylesheet'] ??= $view->assetUrl('css/octopusviewer-viewer-extra.css', 'OctopusViewer', $override = true);

        $args = [
            'attributes' => $attributes,
        ];

        return $view->partial('octopus-viewer/helper/octopusviewer/media-view', $args);
    }

    public function mediaInfo(array $attributes = [])
    {
        $view = $this->getView();

        $attributes['site-slug'] ??= $view->layout()->site->slug();

        $args = [
            'attributes' => $attributes,
        ];

        return $view->partial('octopus-viewer/helper/octopusviewer/media-info', $args);
    }
}
