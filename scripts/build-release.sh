#!/usr/bin/env sh
set -eu

project=$(pwd)
version=$(sed -n 's#.*<Version>\([^<]*\)</Version>.*#\1#p' plugin/MGD_Barrierefrei/info.xml)
case "$version" in [0-9]*.[0-9]*.[0-9]*) ;; *) echo "Ungültige Version: $version" >&2; exit 1;; esac
mkdir -p release
output="release/MGD_Barrierefrei-${version}.zip"
stage=$(mktemp -d)
cp -R plugin/MGD_Barrierefrei "$stage/MGD_Barrierefrei"
(cd "$stage" && zip -qr "$project/$output" MGD_Barrierefrei -x '*/.DS_Store')
shasum -a 256 "$output" > "${output}.sha256"
echo "Paket erstellt: $output"
