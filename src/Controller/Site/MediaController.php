<?php

namespace MediaViewer\Controller\Site;

use Laminas\Mvc\Controller\AbstractActionController;
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
        $mediaId = $this->params()->fromRoute('id');
        $media = $this->api()->read('media', $mediaId)->getContent();

        $view = new ViewModel();
        $view->setVariable('media', $media);
        $view->setTerminal(true);

        $this->getResponse()->getHeaders()->addHeaderLine('Access-Control-Allow-Origin', '*');

        return $view;
    }
}
