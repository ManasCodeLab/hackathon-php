#!/bin/bash
export PHP_INI_SCAN_DIR=/vercel/path0
export PHP_EXTENSIONS="mongodb,curl,openssl,json,mbstring,fileinfo"
composer install --no-dev --optimize-autoloader
composer dump-autoload --optimize 