#!/bin/bash
set -e

echo "🔐 Obfuscating source code..."
rm -rf build/obfuscated
mkdir -p build/obfuscated

php ~/.composer/vendor/bin/scrambler scramble src --output build/obfuscated --config=scrambler.json

echo "✅ Obfuscation done"
