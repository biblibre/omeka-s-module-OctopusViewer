View helpers
============

.. highlight:: php

``octopusViewer``
-----------------

You can use this view helper to display the viewer for all media of an item like this::

    <?= $this->octopusViewer()->forItem($itemRepresentation) ?>
