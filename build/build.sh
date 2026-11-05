#!/usr/bin/env bash
#
# Builds build/plg_content_flickralbum.zip from the plugin sources and prints
# its version and sha512. Runs on macOS and Linux; the release workflow uses it too.

set -euo pipefail

cd "$(dirname "$0")/.."

ZIP="build/plg_content_flickralbum.zip"

rm -f "$ZIP"
zip -r -q "$ZIP" flickralbum.xml language layouts services src -x '*.DS_Store'

if command -v sha512sum >/dev/null 2>&1; then
	SHA512=$(sha512sum "$ZIP" | cut -d ' ' -f 1)
else
	SHA512=$(shasum -a 512 "$ZIP" | cut -d ' ' -f 1)
fi

echo "zip:     $ZIP"
echo "version: $(sed -n 's:.*<version>\(.*\)</version>.*:\1:p' flickralbum.xml)"
echo "sha512:  $SHA512"
