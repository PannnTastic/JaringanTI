@echo off
echo Starting WebSocket Location Tracker Services for IIS...
echo.

echo [1/2] Starting Laravel Reverb WebSocket Server...
start "Reverb Server" cmd /k "php artisan reverb:start --debug"
timeout /t 2 /nobreak >nul

echo [2/2] Starting Queue Worker...
start "Queue Worker" cmd /k "php artisan queue:work --tries=1 --timeout=60"
timeout /t 2 /nobreak >nul

echo.
echo Services started for IIS environment!
echo.
echo - Reverb WebSocket Server: ws://localhost:8080
echo - Laravel App: http://jarti.plnbatam.com (via IIS)
echo - Queue Worker: Running
echo.
echo WebSocket Test URL: http://jarti.plnbatam.com/websocket-test
echo Location Tracker URL: http://jarti.plnbatam.com/admin/real-time-location-tracker
echo.
echo Press any key to close this window...
pause >nul
