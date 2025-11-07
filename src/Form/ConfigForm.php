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

        if (version_compare(\Omeka\Module::VERSION, '4.0') > 0) {
            $info = 'This setting can be replaced by one or several resource page blocks available in the theme settings. In that case, select "No"'; // @translate
            $this->get('octopusviewer_item_show')->setOption('info', $info);
            $this->get('octopusviewer_media_show')->setOption('info', $info);
        }

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
                'label' => 'Show media selector sidebar', // @translate
                'info' => 'This setting only applies to the full viewer (not the media selector block). It can be overriden in the site settings', // @translate
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
            'name' => 'octopusviewer_show_media_selector_block',
            'options' => [
                'label' => 'Show media selector block', // @translate
                'info' => 'This setting only applies to the media selector block (not the media selector sidebar). It can be overriden in the site settings', // @translate
                'value_options' => [
                    'auto' => 'Only if there are several media', // @translate
                    'always' => 'Always', // @translate
                ],
            ],
        ]);

        $this->add([
            'type' => Select::class,
            'name' => 'octopusviewer_show_media_info',
            'options' => [
                'label' => 'Show media info sidebar', // @translate
                'info' => 'This setting only applies to the full viewer (not the media metadata block). It can be overriden in the site settings', // @translate
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

        $this->add([
            'type' => Select::class,
            'name' => 'octopusviewer_show_download_link',
            'options' => [
                'element_group' => 'octopusviewer',
                'label' => 'Show download link', // @translate
                'info' => 'Show a link to download the original file when available (does not work with some media types like Youtube videos for instance)', // @translate
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

        $this->getInputFilter()->add([
            'name' => 'octopusviewer_item_show',
            'required' => false,
        ]);
        $this->getInputFilter()->add([
            'name' => 'octopusviewer_media_show',
            'required' => false,
        ]);
    }
}
