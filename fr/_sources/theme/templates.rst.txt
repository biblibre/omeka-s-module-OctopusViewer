Templates
=========

The following template files can be overriden by themes:

``octopus-viewer/helper/octopusviewer/for-item.phtml``
    Used on the item "show" page and everywhere the view helper's ``forItem``
    method is used.

``view/octopus-viewer/helper/octopusviewer/viewer.phtml``
    Used in the embedded view (when included in an iframe for instance) and
    everywhere the view helper's ``viewer`` method is used.

``view/octopus-viewer/site/media/info.phtml``
    The content of the right panel.

``view/octopus-viewer/site/viewer/media-selector.phtml``
    The content of the left panel.

This is not an exhaustive list. For the full list of template files, look at
`the view directory in the sources
<https://github.com/biblibre/omeka-s-module-OctopusViewer/tree/master/view>`_
