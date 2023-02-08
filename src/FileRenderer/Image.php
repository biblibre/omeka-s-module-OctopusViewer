<?php

namespace MediaViewer\FileRenderer;

use Laminas\View\Renderer\PhpRenderer;
use Omeka\Api\Representation\MediaRepresentation;
use Omeka\Settings\Settings;

class Image implements FileRendererInterface
{
    protected $settings;

    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    public function preRender(PhpRenderer $view, MediaRepresentation $media): void
    {
        $view->headScript()->appendFile($view->assetUrl('vendor/openseadragon/openseadragon.min.js', 'Omeka'));
        $view->headScript()->appendFile($view->assetUrl('js/mediaviewer-openseadragon.js', 'MediaViewer'));
    }

    public function render(PhpRenderer $view, MediaRepresentation $media): string
    {
        $config = [
            'prefixUrl' => $view->assetUrl('vendor/openseadragon/images/', 'Omeka', false, false),
            'showRotationControl' => true,
            'showFlipControl' => true,
            'showFullPageControl' => false,
            'animationTime' => 0.2,
        ];

        $uriTemplate = $this->settings->get('mediaviewer_iiif_image_uri_template');
        if ($uriTemplate) {
            $config['tileSources'] = $this->processUriTemplate($uriTemplate, $media);
            $config['fallbackUrl'] = $media->originalUrl();
        } else {
            $config['tileSources'] = [
                'type' => 'image',
                'url' => $media->originalUrl(),
            ];
        }

        $values = [
            'config' => $config,
        ];

        return $view->partial('media-viewer/partial/file-renderer/image', $values);
    }

    protected function processUriTemplate($uriTemplate, $media)
    {
        $values = [
            '{id}' => $media->id(),
            '{storage_id}' => $media->storageId(),
            '{filename}' => $media->filename(),
            '{extension}' => $media->extension(),
        ];
        $search = array_keys($values);
        $replace = array_values($values);

        return str_replace($search, $replace, $uriTemplate);
    }
}
