#!/bin/bash
set -e

echo "🛠 Starting Build Process..."

bash scripts/version-bump.sh
bash scripts/obfuscate.sh
bash scripts/cleanup.sh

echo "📦 Creating ZIP package..."
mkdir -p build
cd build/obfuscated
zip -r ../package.zip . > /dev/null
cd ../..

echo "✅ Build complete. Output: build/package.zip"
