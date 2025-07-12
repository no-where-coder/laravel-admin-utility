#!/bin/bash
set -e

echo "🔐 Obfuscating using pmaslak/php-obfuscator..."

# Clean previous build
rm -rf build/obfuscated
mkdir -p build/obfuscated

# Run obfuscator via vendor/bin
php vendor/bin/php-obfuscator \
  --source=src \
  --destination=build/obfuscated \
  --obfuscate-variable-name \
  --obfuscate-function-name \
  --obfuscate-class-name \
  --obfuscate-property-name \
  --obfuscate-string-literal

echo "✅ Obfuscation complete"
