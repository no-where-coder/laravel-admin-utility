#!/bin/bash
set -e

echo "🔐 Obfuscating using YAK Pro – Php Obfuscator..."

rm -rf build/obfuscated
mkdir -p build/obfuscated

php yakpro-po/yakpro-po.php \
  src -o build/obfuscated --config-file yakpro-po.cnf

echo "✅ Obfuscation complete"
