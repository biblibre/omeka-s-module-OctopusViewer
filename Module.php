<?php

namespace OctopusViewer;

use Composer\Semver\Comparator;
use Omeka\Module\AbstractModule;
use Laminas\EventManager\Event;
use Laminas\EventManager\SharedEventManagerInterface;
use Laminas\Mvc\Controller\AbstractController;
use Laminas\Mvc\MvcEvent;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\View\Renderer\PhpRenderer;
use OctopusViewer\Form\ConfigForm;
use OctopusViewer\Form\SiteSettingsFieldset;

class Module extends AbstractModule
{
    public function onBootstrap(MvcEvent $event)
    {
        parent::onBootstrap($event);

        $acl = $this->getServiceLocator()->get('Omeka\Acl');
        $acl->allow(null, 'OctopusViewer\Controller\Site\Media');
        $acl->allow(null, 'OctopusViewer\Controller\Site\Viewer');
        $acl->allow(null, 'OctopusViewer\Controller\Pdfjs');
    }

    public function getConfigForm(PhpRenderer $renderer)
    {
        $forms = $this->getServiceLocator()->get('FormElementManager');
        $settings = $this->getServiceLocator()->get('Omeka\Settings');

        $form = $forms->get(ConfigForm::class);
        $form->setData([
            'octopusviewer_iiif_image_uri_template' => $settings->get('octopusviewer_iiif_image_uri_template'),
            'octopusviewer_item_show' => $settings->get('octopusviewer_item_show'),
            'octopusviewer_media_show' => $settings->get('octopusviewer_media_show'),
            'octopusviewer_show_media_selector' => $settings->get('octopusviewer_show_media_selector', 'auto'),
            'octopusviewer_show_media_info' => $settings->get('octopusviewer_show_media_info', 'auto'),
            'octopusviewer_default_media_title' => $settings->get('octopusviewer_default_media_title', 'auto'),
            'octopusviewer_show_download_link' => $settings->get('octopusviewer_show_download_link', 'no'),
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
        $settings->set('octopusviewer_media_show', $formData['octopusviewer_media_show']);
        $settings->set('octopusviewer_show_media_selector', $formData['octopusviewer_show_media_selector']);
        $settings->set('octopusviewer_show_media_info', $formData['octopusviewer_show_media_info']);
        $settings->set('octopusviewer_default_media_title', $formData['octopusviewer_default_media_title']);
        $settings->set('octopusviewer_show_download_link', $formData['octopusviewer_show_download_link']);

        return true;
    }

    public function attachListeners(SharedEventManagerInterface $sharedEventManager)
    {
        $settings = $this->getServiceLocator()->get('Omeka\Settings');
        $showItem = $settings->get('octopusviewer_item_show');
        if ($showItem) {
            $sharedEventManager->attach(
                'Omeka\Controller\Site\Item',
                "view.show.$showItem",
                [$this, 'handleSiteItemViewShow']
            );
        }
        $showMedia = $settings->get('octopusviewer_media_show');
        if ($showMedia) {
            $sharedEventManager->attach(
                'Omeka\Controller\Site\Media',
                "view.show.$showMedia",
                [$this, 'handleSiteMediaViewShow']
            );
        }

        $sharedEventManager->attach('Omeka\Form\SiteSettingsForm', 'form.add_elements', [$this, 'onSiteSettingsFormAddElements']);
        $sharedEventManager->attach('Omeka\Form\SiteSettingsForm', 'form.add_input_filters', [$this, 'onSiteSettingsFormAddInputFilters']);
    }

    public function upgrade($oldVersion, $newVersion, ServiceLocatorInterface $services)
    {
        $connection = $services->get('Omeka\Connection');
        if (Comparator::lessThan($oldVersion, '0.7.0')) {
            $sql = <<<'SQL'
            UPDATE setting SET value = '"media-selector"'
            WHERE id = 'octopusviewer_show_download_link' AND value = '"yes"';
            SQL;
            $connection->exec($sql);

            $sql = <<<'SQL'
            UPDATE site_setting SET value = '"media-selector"'
            WHERE id = 'octopusviewer_show_download_link' AND value = '"yes"';
            SQL;
            $connection->exec($sql);
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

    public function handleSiteMediaViewShow(Event $event)
    {
        $view = $event->getTarget();

        echo $view->octopusViewer()->forMedia($view->media);
    }

    public function onSiteSettingsFormAddElements(Event $event)
    {
        $services = $this->getServiceLocator();
        $forms = $services->get('FormElementManager');
        $siteSettings = $services->get('Omeka\Settings\Site');

        $fieldset = $forms->get(SiteSettingsFieldset::class);
        $fieldset->setName('octopusviewer');
        $fieldset->populateValues([
            'octopusviewer_show_media_selector' => $siteSettings->get('octopusviewer_show_media_selector', ''),
            'octopusviewer_show_media_info' => $siteSettings->get('octopusviewer_show_media_info', ''),
            'octopusviewer_default_media_title' => $siteSettings->get('octopusviewer_default_media_title', ''),
            'octopusviewer_show_download_link' => $siteSettings->get('octopusviewer_show_download_link', ''),
        ]);

        $form = $event->getTarget();

        $groups = $form->getOption('element_groups');
        if (isset($groups)) {
            $groups['octopusviewer'] = $fieldset->getLabel();
            $form->setOption('element_groups', $groups);
            foreach ($fieldset->getElements() as $element) {
                $form->add($element);
            }
        } else {
            $form->add($fieldset);
        }
    }

    public function onSiteSettingsFormAddInputFilters(Event $event)
    {
        $inputFilter = $event->getParam('inputFilter');

        if ($inputFilter->has('octopusviewer')) {
            $inputFilter = $inputFilter->get('octopusviewer');
        }

        $inputFilter->add([
            'name' => 'octopusviewer_show_media_selector',
            'allow_empty' => true,
        ]);
        $inputFilter->add([
            'name' => 'octopusviewer_show_media_info',
            'allow_empty' => true,
        ]);
        $inputFilter->add([
            'name' => 'octopusviewer_default_media_title',
            'allow_empty' => true,
        ]);
        $inputFilter->add([
            'name' => 'octopusviewer_show_download_link',
            'allow_empty' => true,
        ]);
    }
}
