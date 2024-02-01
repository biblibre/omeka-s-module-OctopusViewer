Customize viewer appearance
===========================

octopusviewer-viewer-extra.css
------------------------------

One of the simplest way is to add CSS to a file named
``asset/css/octopusviewer-viewer-extra.css``.

It will be automatically loaded inside the viewer's shadow DOM (which means it
won't affect anything but the viewer).

This requires OctopusViewer 0.5.0 or higher.

pdfjs-viewer.css
----------------

The PDF viewer is a little bit different because it's inside an iframe, so it
requires another CSS file. Create a file named ``asset/css/pdfjs-viewer.css``
and it will be included in the PDF viewer's iframe.

This requires OctopusViewer 0.3.0 or higher.
