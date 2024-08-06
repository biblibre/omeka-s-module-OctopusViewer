<?php

namespace OctopusViewer\Form;

use Laminas\Form\Form;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Select;

class ConfigForm extends Form
{
    public function init()
    {
        $this->add([
            'type' => Select::class,
            'name' => 'octopusviewer_item_show',
            'options' => [
                'label' => 'Show viewer on the item page', // @translate
                'value_options' => [
                    '' => 'No', // @translate
                    'before' => 'Before the item content', // @translate
                    'after' => 'After the item content', // @translate
                ],
            ],
            'attributes' => [
                'id' => 'octopusviewer_item_show',
                'required' => false,
            ],
        ]);

        $this->add([
            'type' => Select::class,
            'name' => 'octopusviewer_media_show',
            'options' => [
                'label' => 'Show viewer on the media page', // @translate
                'value_options' => [
                    '' => 'No', // @translate
                    'before' => 'Before the media content', // @translate
                    'after' => 'After the media content', // @translate
                ],
            ],
            'attributes' => [
                'id' => 'octopusviewer_media_show',
                'required' => false,
            ],
        ]);

        $this->add([
            'type' => Text::class,
            'name' => 'octopusviewer_iiif_image_uri_template',
            'options' => [
                'label' => 'IIIF Image URI template', // @translate
                'info' => 'If set, it will be used to build an IIIF base URI for media stored locally. The following expressions can be used and will be replaced by their corresponding value: {id}, {storage_id}, {filename}, {extension}', // @translate
            ],
            'attributes' => [
                'id' => 'octopusviewer_iiif_image_uri_template',
                'placeholder' => 'https://example.com/iiif/{filename}/info.json',
            ],
        ]);

        $this->add([
            'type' => Select::class,
            'name' => 'octopusviewer_show_media_selector',
            'options' => [
                'label' => 'Show media selector', // @translate
                'info' => 'This setting can be overriden in the site settings', // @translate
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
                'label' => 'Show media info', // @translate
                'info' => 'This setting can be overriden in the site settings', // @translate
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
                'label' => 'Default media title', // @translate
                'info' => 'Text used when a media has no title', // @translate
                'value_options' => [
                    'untitled' => '[Untitled]', // @translate
                    'no_text' => 'No text', // @translate
                ],
            ],
            'attributes' => [
                'id' => 'octopusviewer_default_media_title',
            ],
        ]);

        $this->getInputFilter()->add([
            'name' => 'octopusviewer_item_show',
            'required' => false,
        ]);
    }
}
