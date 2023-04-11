<?php

namespace OctopusViewer;

use Omeka\Module\AbstractModule;
use Laminas\EventManager\Event;
use Laminas\EventManager\SharedEventManagerInterface;
use Laminas\Mvc\Controller\AbstractController;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Renderer\PhpRenderer;
use OctopusViewer\Form\ConfigForm;

class Module extends AbstractModule
{
    public function onBootstrap(MvcEvent $event)
    {
        parent::onBootstrap($event);

        $acl = $this->getServiceLocator()->get('Omeka\Acl');
        $acl->allow(null, 'OctopusViewer\Controller\Site\Media');
        $acl->allow(null, 'OctopusViewer\Controller\Site\Viewer');
    }

    public function getConfigForm(PhpRenderer $renderer)
    {
        $forms = $this->getServiceLocator()->get('FormElementManager');
        $settings = $this->getServiceLocator()->get('Omeka\Settings');

        $form = $forms->get(ConfigForm::class);
        $form->setData([
            'octopusviewer_iiif_image_uri_template' => $settings->get('octopusviewer_iiif_image_uri_template'),
            'octopusviewer_item_show' => $settings->get('octopusviewer_item_show'),
        ]);

        return $renderer->formCollection($form, false);
    }

    public function handleConfigForm(AbstractController $controller)
    {
        $forms = $this->getServiceLocator()->get('FormElementManager');
        $settings = $this->getServiceLocator()->get('Omeka\Settings');

        $form = $forms->get(ConfigForm::class);
        $form->setData($controller->params()->fromPost());
        if (!$form->isValid()) {
            $controller->messenger()->addErrors($form->getMessages());
            return false;
        }

        $formData = $form->getData();
        $settings->set('octopusviewer_iiif_image_uri_template', $formData['octopusviewer_iiif_image_uri_template']);
        $settings->set('octopusviewer_item_show', $formData['octopusviewer_item_show']);

        return true;
    }

    public function attachListeners(SharedEventManagerInterface $sharedEventManager)
    {
        $settings = $this->getServiceLocator()->get('Omeka\Settings');
        $show = $settings->get('octopusviewer_item_show');
        if ($show) {
            $sharedEventManager->attach(
                'Omeka\Controller\Site\Item',
                "view.show.$show",
                [$this, 'handleSiteItemViewShow']
            );
        }
    }

    public function getConfig()
    {
        return require __DIR__ . '/config/module.config.php';
    }

    public function handleSiteItemViewShow(Event $event)
    {
        $view = $event->getTarget();

        echo $view->octopusViewer()->forItem($view->item);
    }
}
