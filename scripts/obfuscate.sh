#!/bin/bash
set -e

echo "🔐 Obfuscating using foobaz/php-obfuscator..."

rm -rf build/obfuscated
mkdir -p build/obfuscated

php php-obfuscator/obfuscate run \
  --source=src \
  --destination=build/obfuscated \
  --strip-comments \
  --rename-variables \
  --rename-functions \
  --rename-classes

echo "✅ Obfuscation complete"
