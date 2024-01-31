# OctopusViewer (module for Omeka S)

OctopusViewer gathers all your Omeka S media in a lightweight viewer.
It's intended to be used as a replacement of other viewers like UniversalViewer
or Mirador.

However, unlike UniversalViewer and Mirador, OctopusViewer is not a IIIF
viewer. In order to use IIIF manifests with OctopusViewer, they should be added
to Omeka S as "IIIF presentation" media (requires Omeka S 4.0.0 or higher).

## Motivation

UniversalViewer and Mirador are great tools but they come with some limitations
when using them in Omeka S:

- They require a IIIF Presentation manifest, which often means an additional
  module should be installed
- They support only a subset of Omeka S media types
- They are not easy to customize in Omeka S themes

The goals of OctopusViewer are:

- it should be able to display all types of media, falling back to a sensible
  default when a media cannot be rendered in a browser
- it should use as few external dependencies as possible, favoring the
  tools that are already available on an empty Omeka S installation (like
  OpenSeadragon)
- it should require no additional Omeka S modules, but should be extensible by
  them
- it should be easily customizable in Omeka S themes

## Features

- A viewer split into 3 panels:
  - the left panel contains a media selector
  - the center panel renders the media
  - the right panel displays metadata for the currently selected media
- Images are displayed within OpenSeadragon, allowing to zoom, rotate, and flip images
- An optional IIIF Image server can be used
- PDF files are displayed with [pdfjs](https://mozilla.github.io/pdf.js/).
- Fullscreen mode

https://user-images.githubusercontent.com/306836/214820277-66234bf1-0db5-4002-b3e6-3f0d0b6da0dd.mp4

## Installation

See general end user documentation for
[installing a module](http://omeka.org/s/docs/user-manual/modules/#installing-modules).

## Documentation

The full documentation can be found here:
https://biblibre.github.io/omeka-s-module-OctopusViewer/

## Sponsors

- [UMR LISA](https://umrlisa.univ-corse.fr)

## License

OctopusViewer is distributed under the GNU General Public License, version 3. The
full text of this license is given in the `LICENSE` file.
