#!/bin/sh
set -e

cleanup() {
    git ls-files ./ | xargs -r -n 1 rm
    rm -rfd ./*
}

copyToTemp() {
    TEMP_DIR="$(mktemp -d --suffix=_doctum-build-php-webdriver)"
    cp -rp build/dist/* "${TEMP_DIR}"
    cp ./scripts/docs-template.html "${TEMP_DIR}/index.html"
}

emptyAndRemoveTemp() {
    mv "${TEMP_DIR}"/* ./
    # Create symlink for main to latest
    ln -s -r ./main ./latest
    # Create symlink for main to master
    ln -s -r ./main ./master
    # Create symlink for main to community
    ln -s -r ./main ./community
    rm -rf "${TEMP_DIR}"
}

commitAndPushChanges() {
    # Push the changes, only if there is changes
    git add -A
    git diff-index --quiet HEAD || git commit -m "Api documentations update ($(date --rfc-3339=seconds --utc))" -m "#apidocs" && if [ -z "${SKIP_PUSH}" ]; then git push; fi
}

if [ ! -d ./build/dist ]; then
    echo 'Missing built docs'
    exit 1
fi

# Remove cache dir, do not upload it
rm -rf ./build/cache

copyToTemp
# Remove build dir, do not upload it
rm -rf ./build

git checkout gh-pages

cleanup
emptyAndRemoveTemp
commitAndPushChanges

git checkout - > /dev/null
