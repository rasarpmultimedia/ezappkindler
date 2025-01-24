#############################################################
Framework: ezAppKindler_v1.4
Architecture: MVC
Language: PHP 5.6
Version: v1.4
Released Date: 20 - 01 - 2018
Developer: Sarpong Abdul-Rahman D. for Rasarp Multimedia Inc.
Country: Ghana.
Contact: +233271957502/+233269063879
Email : fadanash@gmail.com
License :GPL v3.0 Open Source License
#############################################################

ezAppKindler is a Rapid Application Development (RAD) framework developed in PHP implementing MVC architecture and has many build-in libraries to make web development easier and faster.

Directory Structure

+engine - main framework Directory were core components are found

-core – contains main framework core class library scripts
+lib – contains all auxiliary class library scripts for web application development

- components – contains auxiliary scripts such as helper classes for web development, keep all your additional helper classes here.

* public – contains main and site wide css, html and scripts -boiler templates for web development, create all site wide boiler templates here, sample templates provided for guidance.
  - multimedia – contains media uploaded like pictures, videos, audio files for website
* vendor - contains scripts from third parties vendors libraries and plugins

- app – applications main directory for website, web applications etc.
  +webroot – contains main website files and assets or public website scripts +

  - view – view directory keep all view files and directorties here.

- includes – contains directory path setting, initiations files and other sever side includes

  - config – contains database configuration ini file change setting to configure databases and other settings.

* schema – contains sample database dump files and SQL scripts for database creation.

Change Logs
PHP Namespace Added
