<?php

namespace OctopusViewer\Controller\Site;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;

class MediaController extends AbstractActionController
{
    public function renderAction()
    {
        $mediaId = $this->params()->fromRoute('id');
        $media = $this->api()->read('media', $mediaId)->getContent();

        $view = new ViewModel();
        $view->setVariable('media', $media);
        $view->setTerminal(true);

        $this->getResponse()->getHeaders()->addHeaderLine('Access-Control-Allow-Origin', '*');

        return $view;
    }

    public function infoAction()
    {
        $partialHelper = $this->viewHelpers()->get('partial');

        $mediaId = $this->params()->fromRoute('id');
        $media = $this->api()->read('media', $mediaId)->getContent();

        $values = [
            'media' => $media,
        ];

        $view = new JsonModel([
            'content' => $partialHelper('octopus-viewer/site/media/info', $values),
            'footer' => $partialHelper('octopus-viewer/site/media/info-footer', $values),
        ]);

        $this->getResponse()->getHeaders()->addHeaderLine('Access-Control-Allow-Origin', '*');

        return $view;
    }
}
