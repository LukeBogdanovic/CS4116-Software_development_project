#!/bin/bash

echo "====================================================================="
echo "Checking if xdg-open is installed on system"
echo "====================================================================="


if ! command -v xdg-open &> /dev/null
then
    echo "====================================================================="
    echo "xdg-open is being installed on this system"
    echo "====================================================================="
    apt install xdg-utils
    echo "====================================================================="
    echo "xdg-open is now installed on system"
    echo "====================================================================="
else
    echo "====================================================================="
    echo "xdg-open is installed on system"
    echo "====================================================================="
fi


echo "====================================================================="
echo "Starting WebPage on localhost:8000"
echo "====================================================================="
xdg-open "http://localhost:8000"


echo "====================================================================="
echo "Changing directory to project home directory"
echo "====================================================================="
cd ..


if [ $(ls | grep index.php) ]
then
    if command -v php &> /dev/null
    then
        echo "====================================================================="
        echo "Starting PHP development Server on localhost"
        echo "====================================================================="
        php -S 127.0.0.1:8000
    else
        echo "====================================================================="
        echo "PHP client isn't installed on this system"
        echo "====================================================================="
        echo "====================================================================="
        echo "Installing PHP client on this system"
        echo "====================================================================="
        apt install php7.4-cli
        echo "====================================================================="
        echo "PHP client is now installed on this system"
        echo "====================================================================="
        echo "====================================================================="
        echo "Starting PHP development Server on localhost"
        echo "====================================================================="
        php -S 127.0.0.1:8000
    fi
else
    echo "====================================================================="
    echo "No File called index.php present in project home directory. Exiting."
    echo "====================================================================="
    exit
fi
