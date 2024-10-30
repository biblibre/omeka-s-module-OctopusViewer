# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.8.1] - 2024-10-30

- Remove viewer from DOM if there is no media to display

## [0.8.0] - 2024-10-24

### Added
- Add ability to show viewer on media page (global setting)
- Add ability for the resource page block to be placed in the media page

### Fixed
- Fix viewer when Omeka is in a subdirectory

## [0.7.3] - 2024-09-12

- Fix display of PDFs when their URLs have not the same origin as Omeka URL

## [0.7.2] - 2024-09-02

- Use `target="_blank"` instead of `download` attribute as the `download`
  attribute only works with same-origin URLs (which is not guaranteed, for
  instance with the AnyCloud module)

## [0.7.1] - 2024-08-29

- Trim media information panel footer so that the `:empty` CSS pseudo-selector
  can match.

## [0.7.0] - 2024-08-29

- Allow to display a download link in the media information panel

## [0.6.0] - 2024-08-28

- Add an optional download link for each downloadable media

## [0.5.5] - 2024-06-13

- Fix site setting not getting saved

## [0.5.4] - 2024-05-10

- Fix ACL for `OctopusViewer\Controller\PdfJs`

## [0.5.3] - 2024-05-06

- Make OpenSeadragon's prefixUrl absolute also on Omeka S < 4.0 (IIIF renderer)

## [0.5.2] - 2024-03-14

- Fix inclusion of pdfjs custom CSS when embbeded in a different site

## [0.5.1] - 2024-03-13

- Allow pdf.js iframe to be embedded in other sites

## [0.5.0] - 2024-02-01

### Added
- Added the ability for themes to customize the viewer appearance by overriding
  a CSS file

## [0.4.2] - 2024-01-26

### Fixed
- Fixed error when no other modules enables ViewJsonStrategy

## [0.4.1] - 2023-12-12

### Fixed
- Fixed error on site configuration page (Omeka S 3 only)
- Fixed label of resource page block layout

## [0.4.0] - 2023-11-14

### Added
- Add global and site settings to control if side panels should be displayed
- Add global and site settings to control the text displayed when a media has
  no title ("[Untitled]" or no text)

### Changed
- Hide all open, print, download, and editor buttons in PDF viewer

### Fixed
- Make OpenSeadragon's prefixUrl absolute also on Omeka S < 4.0

## [0.3.0] - 2023-10-04

### Changed

- Always use PDF.js to be able to customize the viewer
- The "Download" and "Open File" buttons of the PDF viewer are now hidden by
  default (can be made visible by themes)

## [0.2.1] - 2023-04-12

### Changed

- Show "[Untitled]" instead of the media source in the media selector

### Fixed

- Fixed margin around property names in the info sidebar
- Fixed the position of thumbnail in the media selector

## [0.2.0] - 2023-04-11

Renamed module from MediaViewer to OctopusViewer

If upgrading from 0.1.0, first uninstall MediaViewer, remove the module
directory and do a fresh install of OctopusViewer

## [0.1.0] - 2023-01-26

Initial release

[0.8.1]: https://github.com/biblibre/omeka-s-module-OctopusViewer/releases/tag/v0.8.1
[0.8.0]: https://github.com/biblibre/omeka-s-module-OctopusViewer/releases/tag/v0.8.0
[0.7.3]: https://github.com/biblibre/omeka-s-module-OctopusViewer/releases/tag/v0.7.3
[0.7.2]: https://github.com/biblibre/omeka-s-module-OctopusViewer/releases/tag/v0.7.2
[0.7.1]: https://github.com/biblibre/omeka-s-module-OctopusViewer/releases/tag/v0.7.1
[0.7.0]: https://github.com/biblibre/omeka-s-module-OctopusViewer/releases/tag/v0.7.0
[0.6.0]: https://github.com/biblibre/omeka-s-module-OctopusViewer/releases/tag/v0.6.0
[0.5.5]: https://github.com/biblibre/omeka-s-module-OctopusViewer/releases/tag/v0.5.5
[0.5.4]: https://github.com/biblibre/omeka-s-module-OctopusViewer/releases/tag/v0.5.4
[0.5.3]: https://github.com/biblibre/omeka-s-module-OctopusViewer/releases/tag/v0.5.3
[0.5.2]: https://github.com/biblibre/omeka-s-module-OctopusViewer/releases/tag/v0.5.2
[0.5.1]: https://github.com/biblibre/omeka-s-module-OctopusViewer/releases/tag/v0.5.1
[0.5.0]: https://github.com/biblibre/omeka-s-module-OctopusViewer/releases/tag/v0.5.0
[0.4.2]: https://github.com/biblibre/omeka-s-module-OctopusViewer/releases/tag/v0.4.2
[0.4.1]: https://github.com/biblibre/omeka-s-module-OctopusViewer/releases/tag/v0.4.1
[0.4.0]: https://github.com/biblibre/omeka-s-module-OctopusViewer/releases/tag/v0.4.0
[0.3.0]: https://github.com/biblibre/omeka-s-module-OctopusViewer/releases/tag/v0.3.0
[0.2.1]: https://github.com/biblibre/omeka-s-module-OctopusViewer/releases/tag/v0.2.1
[0.2.0]: https://github.com/biblibre/omeka-s-module-OctopusViewer/releases/tag/v0.2.0
[0.1.0]: https://github.com/biblibre/omeka-s-module-OctopusViewer/releases/tag/v0.1.0
