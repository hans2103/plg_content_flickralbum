# Flickr Album content plugin for Joomla

Joomla content plugin that replaces `{flickr album=ALBUMID user=USERID}` with an embedded Flickr album.
`user` is optional when a Flickr user ID is set in the plugin settings. Values may be quoted: `{flickr album="ALBUMID" user="USERID"}`.
A tag that an editor wrapped in `<p>`, `<div>` or `<span>` is replaced including that wrapper.

## Requirements

* Joomla 5 or 6
* PHP 8.1 or newer

## Installation and updates

Download `plg_content_flickralbum.zip` from the [latest release](https://github.com/hans2103/plg_content_flickralbum/releases/latest) and install it via **System → Install → Extensions**. Then enable **Content - Flickr Album** and set the Flickr user ID.

From 26.46 on, Joomla finds updates itself through [`update.xml`](update.xml). Installs of 26.45.0 or older have no update server registered, so install the next version once by hand.

## Content Security Policy

The album is an iframe from `https://embedr.flickr.com/photosets/ALBUMID`, the host Flickr's own embed code uses.
When the System - HTTP Headers plugin sends a Content-Security-Policy, add `https://embedr.flickr.com` to `frame-src` (and to `fullscreen` in the Permissions-Policy, if you set one).

The plugin doesn't use `www.flickr.com/…/player/`: that page runs Flickr's cookie consent manager, which reloads the iframe endlessly when the browser blocks third-party cookies.

## Finding your Flickr user ID

* **Profile URL:** `https://www.flickr.com/photos/yourusername/` or `https://www.flickr.com/photos/12345678@N00/`. The last part is your user ID.
* **Album URL:** in `https://www.flickr.com/photos/98765432@N04/albums/72177720301234567` the user ID is `98765432@N04` and the album ID is `72177720301234567`.

## Development

* `build/build.sh` builds `build/plg_content_flickralbum.zip` and prints its version and sha512.
* `build/prepare-release.sh` prepares a release in the working tree (see below).
* CI (`.github/workflows/ci.yml`) validates the XML, PHP syntax and language files, and builds the package on every push to `main` and every pull request.

## Releasing

Versions follow `YY.WW.PATCH`: year, ISO week, patch number (`26.46.0` is the first release in week 46 of 2026).

1. Merge the changes into `main`, each with a line under `## [Unreleased]` in `CHANGELOG.md`.
2. Start the release workflow, from **Actions → Release → Run workflow** on GitHub or with:

   ```bash
   gh workflow run release.yml
   ```

   Leave the version empty to get the next one for the current week (`26.46.0`, then `26.46.1`, …), or pass one: `gh workflow run release.yml -f version=26.46.0`.

The workflow (`.github/workflows/release.yml`) runs `build/prepare-release.sh`, which renames `## [Unreleased]` to `## [VERSION] - DATE` (with a new empty Unreleased section above), sets the version in `flickralbum.xml`, builds the zip and points `update.xml` at the new release. The workflow then commits that to `main`, tags it, and creates the GitHub release with the changelog section as notes and the zip attached. It stops when the version isn't `YY.WW.PATCH`, the tag already exists or there is nothing under `## [Unreleased]`.

To see what a release would change without publishing anything, run `build/prepare-release.sh` locally and undo it with `git checkout .`.

## Support

This plugin is primarily built for HKweb projects, so their use cases come first. Other features or use cases are considered case by case.
