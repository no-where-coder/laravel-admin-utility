#!/bin/bash
set -e

echo "🔢 Checking latest git tag..."
latest_tag=$(git tag --sort=-v:refname | head -n 1)

if [[ "$latest_tag" =~ ^v[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
    echo "🔖 Found latest tag: $latest_tag"
    version=${latest_tag:1}
    IFS='.' read -r major minor patch <<< "$version"
    new_tag="v$major.$minor.$((patch+1))"
else
    echo "No tag found. Starting at v1.0.0"
    new_tag="v1.0.0"
fi

git config user.name "github-actions"
git config user.email "actions@github.com"

git tag "$new_tag"
git push origin "$new_tag"

echo "$new_tag" > version.txt