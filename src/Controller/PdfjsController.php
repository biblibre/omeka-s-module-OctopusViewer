<?php

namespace OctopusViewer\Controller;

use Laminas\Mvc\Controller\AbstractActionController;

class PdfjsController extends AbstractActionController
{
    public function viewerAction()
    {
        $response = $this->getResponse();

        $response->getHeaders()->addHeaderLine('Content-Security-Policy', "frame-ancestors *");

        $filepath = dirname(__DIR__, 2) . '/asset/vendor/pdf.js/web/viewer.html';
        $contents = file_get_contents($filepath);

        $viewHelpers = $this->viewHelpers();
        $serverUrl= $viewHelpers->get('serverUrl');
        $assetUrl= $viewHelpers->get('assetUrl');
        $escapeHtmlAttr = $viewHelpers->get('escapeHtmlAttr');

        $css_url = $serverUrl($assetUrl('css/pdfjs-viewer.css', 'OctopusViewer', $override = true));
        $link = '<link rel="stylesheet" href="viewer.css">';
        $links = $link . sprintf('<link rel="stylesheet" href="%s">', $escapeHtmlAttr($css_url));
        $contents = str_replace($link, $links, $contents);

        $response->setContent($contents);

        return $response;
    }
}
