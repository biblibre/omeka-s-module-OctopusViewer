View helpers
============

.. highlight:: php

``octopusViewer``
-----------------

You can use this view helper to display the viewer for all media of an item like this::

    <?= $this->octopusViewer()->forItem($itemRepresentation) ?>

Or you can display any media matching some criteria::

    <?= $this->octopusViewer()->viewer($media_query, $viewer_title) ?>

For instance, ::

    <?= $this->octopusViewer()->viewer(
        [
            'media_type' => 'image/jpeg',
            'item_id' => $item->id(),
        ],
        'Pictures'
    ) ?>

The list of available parameters can be found in `Omeka S developer documentation (API reference) <https://omeka.org/s/docs/developer/api/api_reference/#parameters-for-media>`_.
