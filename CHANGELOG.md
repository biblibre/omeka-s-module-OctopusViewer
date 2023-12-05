# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased

### Fixed
- Fixed error on site configuration page (Omeka S 3 only)

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

[0.4.0]: https://github.com/biblibre/omeka-s-module-OctopusViewer/releases/tag/v0.4.0
[0.3.0]: https://github.com/biblibre/omeka-s-module-OctopusViewer/releases/tag/v0.3.0
[0.2.1]: https://github.com/biblibre/omeka-s-module-OctopusViewer/releases/tag/v0.2.1
[0.2.0]: https://github.com/biblibre/omeka-s-module-OctopusViewer/releases/tag/v0.2.0
[0.1.0]: https://github.com/biblibre/omeka-s-module-OctopusViewer/releases/tag/v0.1.0
