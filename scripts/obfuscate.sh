#!/bin/bash
set -e

echo "🔐 Obfuscating with naneau/php-obfuscator..."

rm -rf build/obfuscated
mkdir -p build/obfuscated

php vendor/naneau/php-obfuscator/obfuscate.php obfuscate.config.php

echo "✅ Obfuscation done"
