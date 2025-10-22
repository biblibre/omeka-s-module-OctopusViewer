<?php

namespace OctopusViewer\Form;

use Laminas\Form\Element\Select;
use Laminas\Form\Fieldset;

class SiteSettingsFieldset extends Fieldset
{
    public function init()
    {
        $this->setLabel('Octopus Viewer'); // @translate

        $this->add([
            'type' => Select::class,
            'name' => 'octopusviewer_show_media_selector',
            'options' => [
                'element_group' => 'octopusviewer',
                'label' => 'Show media selector', // @translate
                'empty_option' => 'Use global setting', // @translate
                'value_options' => [
                    'auto' => 'Only if there are several media', // @translate
                    'always' => 'Always', // @translate
                    'never' => 'Never', // @translate
                ],
            ],
            'attributes' => [
                'id' => 'octopusviewer_show_media_selector',
            ],
        ]);

        $this->add([
            'type' => Select::class,
            'name' => 'octopusviewer_show_media_info',
            'options' => [
                'element_group' => 'octopusviewer',
                'label' => 'Show media info', // @translate
                'empty_option' => 'Use global setting', // @translate
                'value_options' => [
                    'always' => 'Always', // @translate
                    'never' => 'Never', // @translate
                ],
            ],
            'attributes' => [
                'id' => 'octopusviewer_show_media_info',
            ],
        ]);

        $this->add([
            'type' => Select::class,
            'name' => 'octopusviewer_default_media_title',
            'options' => [
                'element_group' => 'octopusviewer',
                'label' => 'Default media title', // @translate
                'info' => 'Text used when a media has no title', // @translate
                'empty_option' => 'Use global setting', // @translate
                'value_options' => [
                    'untitled' => '[Untitled]', // @translate
                    'no_text' => 'No text', // @translate
                ],
            ],
            'attributes' => [
                'id' => 'octopusviewer_default_media_title',
            ],
        ]);

        $this->add([
            'type' => Select::class,
            'name' => 'octopusviewer_show_download_link',
            'options' => [
                'element_group' => 'octopusviewer',
                'label' => 'Show download link', // @translate
                'info' => 'Show a link to download the original file when available (does not work with some media types like Youtube videos for instance)', // @translate
                'empty_option' => 'Use global setting', // @translate
                'value_options' => [
                    'no' => 'No', // @translate
                    'media-selector' => 'In the media selector panel', // @translate
                    'controls' => 'In the controls (below media view by default)', // @translate
                    'media-selector|controls' => 'In the media selector panel and in the controls', // @translate
                ],
            ],
            'attributes' => [
                'id' => 'octopusviewer_show_download_link',
            ],
        ]);
    }
}
