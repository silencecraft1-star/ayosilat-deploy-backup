@echo off
echo Menjalankan git pull...

git restore .

git pull https://github.com/silencecraft1-star/ayosilat-deploy-backup.git main

echo.
echo Menjalankan npm run prod...
npm run prod

echo.
echo Selesai!
pause