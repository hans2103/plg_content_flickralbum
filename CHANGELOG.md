# Changelog

All notable changes to this plugin are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).
Version numbers follow `YY.WW.PATCH`: year, ISO week, patch number.

## [Unreleased]

### Added
- Update server: Joomla now finds new versions through `update.xml`.
- Dutch (nl-NL) language files.
- CI checks (XML, PHP syntax, language files, package contents) and a release workflow triggered by a version tag.
- GPL-3.0 licence text.

### Changed
- The plugin description and README name `https://embedr.flickr.com` as the host to allow in `frame-src`.
- Minimum PHP version in the update server is 8.1, the minimum of Joomla 5.
- `manifest.xml` is renamed to `update.xml`, like the other HKweb plugins.
- `build/build.sh` only packs files that exist and runs on macOS and Linux.
- README rewritten: usage, CSP, updates and the release process.

## [26.45.0] - 2026-11-05

### Fixed
- The embedded album no longer flickers and can be navigated again. The iframe now loads `https://embedr.flickr.com/photosets/ALBUMID` instead of `www.flickr.com/photos/USER/albums/ALBUMID/player/`, whose cookie consent manager reloaded the player endlessly when the browser blocks third-party cookies.

### Changed
- The update server offers the plugin to Joomla 5 and 6.

**Check before updating:** sites with a Content-Security-Policy must allow `https://embedr.flickr.com` in `frame-src`.

## [25.27.1] - 2025-06-30

### Changed
- Version and update server bump.

## [25.27.0] - 2025-06-30

### Changed
- The `{flickr}` tag is also replaced when an editor wraps it in `<p>`, `<div>` or `<span>`; the wrapper is removed.

## [25.26.2] - 2025-06-27

### Changed
- The iframe is rendered through a layout file (`layouts/media/flickralbum.php`) instead of being hardcoded.

## [25.26.1] - 2025-06-27

### Fixed
- Package build no longer references a directory that doesn't exist.

## [25.26.0] - 2025-06-27

### Added
- Initial release: `{flickr album=ALBUMID user=USERID}` embeds a public Flickr album; the user ID can be set in the plugin settings.
