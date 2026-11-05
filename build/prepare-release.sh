#!/usr/bin/env bash
#
# Prepares a release in the working tree, without committing or tagging:
#
#   1. picks the version: the argument, or YY.WW.PATCH for the current ISO week
#      (PATCH is one higher than the highest existing tag of that week)
#   2. writes the "## [Unreleased]" section of CHANGELOG.md to build/RELEASE_NOTES.md
#      and renames it to "## [VERSION] - DATE", with a new empty Unreleased section above
#   3. sets the version in flickralbum.xml
#   4. builds build/plg_content_flickralbum.zip
#   5. points update.xml at the new release (version, info URL, download URL, sha512)
#
# Prints "version=VERSION" as its last line. Used by .github/workflows/release.yml;
# runs on macOS and Linux, so it can be tried locally (undo with `git checkout .`).
#
# Usage: build/prepare-release.sh [YY.WW.PATCH]

set -euo pipefail

cd "$(dirname "$0")/.."

ZIP="build/plg_content_flickralbum.zip"
NOTES="build/RELEASE_NOTES.md"

# 1. Version
VERSION="${1:-}"

if [ -z "$VERSION" ]; then
	PREFIX="$(date -u +%g).$((10#$(date -u +%V)))"
	LAST=$(git tag -l "$PREFIX.*" | awk -F. '$3 ~ /^[0-9]+$/ { print $3 }' | sort -n | tail -n 1)
	VERSION="$PREFIX.$(( ${LAST:--1} + 1 ))"
fi

if [[ ! "$VERSION" =~ ^[0-9]{2}\.[0-9]{1,2}\.[0-9]+$ ]]; then
	echo "Version $VERSION is not in YY.WW.PATCH format" >&2
	exit 1
fi

if git rev-parse -q --verify "refs/tags/$VERSION" >/dev/null; then
	echo "Tag $VERSION already exists" >&2
	exit 1
fi

# 2. Changelog
awk '
	$0 == "## [Unreleased]" { found = 1; next }
	found && /^## \[/ { exit }
	found { print }
' CHANGELOG.md > "$NOTES"

if ! grep -q '[^[:space:]]' "$NOTES"; then
	echo "CHANGELOG.md has nothing under ## [Unreleased]" >&2
	exit 1
fi

awk -v version="$VERSION" -v date="$(date -u +%F)" '
	$0 == "## [Unreleased]" && !done { print; print ""; print "## [" version "] - " date; done = 1; next }
	{ print }
' CHANGELOG.md > CHANGELOG.md.tmp
mv CHANGELOG.md.tmp CHANGELOG.md

# 3. Plugin manifest
sed -i.bak "s|<version>.*</version>|<version>$VERSION</version>|" flickralbum.xml

# 4. Package
build/build.sh >/dev/null

if command -v sha512sum >/dev/null 2>&1; then
	SHA512=$(sha512sum "$ZIP" | cut -d ' ' -f 1)
else
	SHA512=$(shasum -a 512 "$ZIP" | cut -d ' ' -f 1)
fi

# 5. Update server
sed -i.bak \
	-e "s|<version>.*</version>|<version>$VERSION</version>|" \
	-e "s|releases/tag/[^<]*</infourl>|releases/tag/$VERSION</infourl>|" \
	-e "s|releases/download/[^/]*/plg_content_flickralbum.zip|releases/download/$VERSION/plg_content_flickralbum.zip|" \
	-e "s|<sha512>.*</sha512>|<sha512>$SHA512</sha512>|" \
	update.xml

rm -f flickralbum.xml.bak update.xml.bak

echo "sha512=$SHA512"
echo "version=$VERSION"
