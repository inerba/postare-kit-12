#!/bin/sh
set -e

echo "Deploying application ..."

# Path di nodejs
NODEJS_PATH="/home/ams/.nvm/versions/node/v24.4.1/bin"

# Imposta a 1 per eseguire deploy completo, 0 per saltare la parte facoltativa
DO_FULL_DEPLOY=0

# Imposta a 1 per eseguire il build degli assets, 0 per saltare
DO_BUILD_ASSETS=0

if [ "$DO_FULL_DEPLOY" -eq 1 ]; then
# Modalità manutenzione
(php artisan down) || true

    # Installa dipendenze
    composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader

    # Esegui migrazioni
    php artisan migrate --force

    # Esci dalla modalità manutenzione
    php artisan up
else
    echo "Skipping full deploy."
fi

    # Svuota cache e ottimizza sempre
    php artisan optimize

if [ "$DO_BUILD_ASSETS" -eq 1 ]; then
    # Esegui il build degli assets
    export PATH="$NODEJS_PATH:$PATH"

    # Cambia il comando in base alle tue esigenze, puoi metterne più se necessario
    npm run build:all
else
    echo "Skipping asset build."
fi

# Define the filename
filename='deploy-log.txt'
today=$(date +"%Y-%m-%d %H:%M:%S")
newtext="Ultimo deploy: $today"

# Check the new text is empty or not
if [ "$newtext" != "" ]; then
      # Append the text by using '>>' symbol
      echo $newtext >> $filename
fi

echo "Application deployed!"
