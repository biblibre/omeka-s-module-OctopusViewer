<?php

namespace OctopusViewer\Controller\Site;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Omeka\File\Store\StoreInterface;

class MediaController extends AbstractActionController
{
    protected StoreInterface $fileStore;

    public function __construct(StoreInterface $fileStore)
    {
        $this->fileStore = $fileStore;
    }

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

    public function downloadAction()
    {
        $mediaId = $this->params()->fromRoute('id');
        $media = $this->api()->read('media', $mediaId)->getContent();

        $fileStore = $this->getFileStore();
        if (method_exists($fileStore, 'getLocalPath')) {
            $extension = $media->extension();
            $storagePath = sprintf('original/%s%s', $media->storageId(), isset($extension) ? ".$extension" : '');
            $localPath = $fileStore->getLocalPath($storagePath);
            if (is_readable($localPath)) {
                $filename = $localPath;
            }
        } else {
            $filename = $media->originalUrl();
        }

        if (!isset($filename)) {
            throw new \Omeka\Mvc\Exception\NotFoundException();
        }

        $response = new \Laminas\Http\PhpEnvironment\Response();
        $response->getHeaders()->addHeaderLine('Content-Type', $media->mediaType());
        $response->getHeaders()->addHeaderLine('Content-Length', $media->size());
        $response->getHeaders()->addHeaderLine('Content-Disposition', sprintf('attachment; filename="%s"', $media->source()));

        // Disable output buffer to prevent memory issues
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        $response->sendHeaders();
        readfile($filename);
        exit;
    }

    protected function getFileStore(): StoreInterface
    {
        return $this->fileStore;
    }
}
