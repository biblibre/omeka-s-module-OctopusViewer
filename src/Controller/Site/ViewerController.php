<?php

namespace OctopusViewer\Controller\Site;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use OctopusViewer\MediaRenderer\Manager as MediaRendererManager;

class ViewerController extends AbstractActionController
{
    protected $mediaRendererManager;

    public function __construct(MediaRendererManager $mediaRendererManager)
    {
        $this->mediaRendererManager = $mediaRendererManager;
    }

    public function mediaSelectorAction()
    {
        $item_id = $this->params()->fromQuery()['item_id'];
        $item = $this->api()->read('items', $item_id)->getContent();
        $medias = $item->media();

        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setVariable('medias', $medias);

        $this->getResponse()->getHeaders()->addHeaderLine('Access-Control-Allow-Origin', '*');

        return $view;
    }

    public function jsDependenciesAction()
    {
        $jsDependencies = [];

        $mediaRendererNames = $this->mediaRendererManager->getRegisteredNames();
        foreach ($mediaRendererNames as $mediaRendererName) {
            $mediaRenderer = $this->mediaRendererManager->get($mediaRendererName);
            $mediaRendererJsDependencies = $mediaRenderer->getJsDependencies($this->viewHelpers());
            $jsDependencies = array_merge($jsDependencies, $mediaRendererJsDependencies);
        }

        $view = new JsonModel(['jsDependencies' => $jsDependencies]);

        $this->getResponse()->getHeaders()->addHeaderLine('Access-Control-Allow-Origin', '*');

        return $view;
    }

    public function embedAction()
    {
        $view = new ViewModel();
        $view->setTerminal(true);

        return $view;
    }
}
