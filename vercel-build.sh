#!/bin/bash
composer install --no-dev --optimize-autoloader
composer dump-autoload --optimize 