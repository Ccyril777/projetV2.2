Getting Started for Projects
Prerequisites

    Check PHP 7.3 is installed
    Check Symfony CLI is installed
    Check MariaDb 10.4 is installed
    Check Composer 1.9 is installed
    Check Yarn 1.21 & NodeJS 12 are installed
    
Install

    Clone this project
    Run composer install
    Run yarn install

Create Database

    Connect to mySQL with your account
    Run CREATE DATABASE <dbname>;
    Run CREATE USER <user>@localhost IDENTIFIED BY '<password>';
    Run GRANT ALL ON <dbname> . * TO <user>@localhost;
    Move into the directory and cp .env .env.local file. This one is not committed to the shared repository. Set db_name to your database name.
   
    Change the user and the password in the same file by your MySQL user and password.
    Run php bin/console m:m
    Run php bin/console d:m:m
    Run php bin/console d:f:l
    
Working

    Run yarn run dev --watch to launch your local server for assets
    Run symony server:start to launch your local php web server
    Role Admin is admin@admin.com with password : password

