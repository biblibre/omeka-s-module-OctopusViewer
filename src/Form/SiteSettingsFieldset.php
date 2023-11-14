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
    }
}
