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

    public function mediaListAction()
    {
        $query = $this->params()->fromQuery();
        if (count(array_keys($query)) === 1 && array_key_first($query) === 'item_id') {
            // Special case to fetch all media of an item in the correct order
            $item = $this->api()->read('items', $query['item_id'])->getContent();
            $medias = $item->media();
        } else {
            $medias = $this->api()->search('media', $query)->getContent();
        }

        //$medias = array_map(fn ($media) => $media->getReference(), $medias);

        $view = new JsonModel(['media' => $medias]);

        $this->getResponse()->getHeaders()->addHeaderLine('Access-Control-Allow-Origin', '*');

        return $view;
    }

    public function mediaSelectorAction()
    {
        $query = $this->params()->fromQuery();
        if (count(array_keys($query)) === 1 && array_key_first($query) === 'item_id') {
            // Special case to fetch all media of an item in the correct order
            $item = $this->api()->read('items', $query['item_id'])->getContent();
            $medias = $item->media();
        } else {
            $medias = $this->api()->search('media', $query)->getContent();
        }

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
