@echo off
REM Create base directory
mkdir reetech-group-plugin

REM Create subdirectories
mkdir reetech-group-plugin\includes
mkdir reetech-group-plugin\assets
mkdir reetech-group-plugin\assets\js
mkdir reetech-group-plugin\assets\css
mkdir reetech-group-plugin\templates
mkdir reetech-group-plugin\templates\partials
mkdir reetech-group-plugin\templates\pages

REM Create PHP files inside includes
echo. > reetech-group-plugin\includes\class-asset-loader.php
echo. > reetech-group-plugin\includes\class-rest-api.php
echo. > reetech-group-plugin\includes\class-shortcode-handler.php
echo. > reetech-group-plugin\includes\class-database-manager.php
echo. > reetech-group-plugin\includes\class-cache-manager.php

REM Create partials
echo. > reetech-group-plugin\templates\partials\header.php
echo. > reetech-group-plugin\templates\partials\footer.php

REM Create page templates
echo. > reetech-group-plugin\templates\pages\edit.php
echo. > reetech-group-plugin\templates\pages\view.php

REM Create main plugin file
echo. > reetech-group-plugin\reetech-group-plugin.php

echo Folder and file structure created successfully.
pause
