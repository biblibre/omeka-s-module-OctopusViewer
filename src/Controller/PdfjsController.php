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
        $response->setContent(file_get_contents($filepath));

        return $response;
    }
}
