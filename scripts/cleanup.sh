#!/bin/bash
set -e

echo "🧹 Cleaning up build..."
cd build/obfuscated

rm -rf tests .git .github scripts .env phpunit.xml composer.lock

echo "✅ Cleanup complete"
