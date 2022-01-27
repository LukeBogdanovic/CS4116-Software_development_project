@ECHO off

:: Section 1: Opening webpage in browser
ECHO "===================================="
ECHO "Starting webpage at localhost:8000"
ECHO "===================================="
START "http://localhost:8000"

:: Section 2: Starting php development server
ECHO "================================="
ECHO "Changing directory to project home directory"
ECHO "================================="
cd C:\Dev\college\CS4116-Software_development_project\
ECHO "================================="
ECHO "Starting PHP development Server"
ECHO "================================="
php -S 127.0.0.1:8000