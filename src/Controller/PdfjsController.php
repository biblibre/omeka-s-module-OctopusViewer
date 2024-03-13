<?php

namespace OctopusViewer\Controller;

use Laminas\Mvc\Controller\AbstractActionController;

class PdfjsController extends AbstractActionController
{
    public function viewerAction()
    {
        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine('Content-Security-Policy', "frame-ancestors *");

        $file = dirname(__DIR__, 2) . '/asset/vendor/pdf.js/web/viewer.html';

        $file_contents = file_get_contents($file);
        $response->setContent($file_contents);

        return $response;
    }
}
