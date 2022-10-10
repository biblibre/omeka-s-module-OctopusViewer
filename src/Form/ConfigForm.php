<?php

namespace MediaViewer\Form;

use Laminas\Form\Form;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Select;

class ConfigForm extends Form
{
    public function init()
    {
        $this->add([
            'type' => Select::class,
            'name' => 'mediaviewer_item_show',
            'options' => [
                'label' => 'Show viewer on the item page', // @translate
                'value_options' => [
                    '' => 'No', // @translate
                    'before' => 'Before the item content', // @translate
                    'after' => 'After the item content', // @translate
                ],
            ],
            'attributes' => [
                'id' => 'mediaviewer_item_show',
                'required' => false,
            ],
        ]);

        $this->add([
            'type' => Text::class,
            'name' => 'mediaviewer_iiif_image_uri_template',
            'options' => [
                'label' => 'IIIF Image URI template', // @translate
                'info' => 'If set, it will be used to build an IIIF base URI for media stored locally. The following expressions can be used and will be replaced by their corresponding value: {id}, {storage_id}, {filename}, {extension}', // @translate
            ],
            'attributes' => [
                'id' => 'mediaviewer_iiif_image_uri_template',
                'placeholder' => 'https://example.com/iiif/{filename}/info.json',
            ],
        ]);

        $this->getInputFilter()->add([
            'name' => 'mediaviewer_item_show',
            'required' => false,
        ]);
    }
}
