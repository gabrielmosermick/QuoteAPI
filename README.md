# QuoteShoutApplication

## Introduction

This is a RESTful API application to give a list of quotes
from a person in a shouting form with a limit of records.
The app also uses a cache layer for retrieving results.
Made with Zend Framework 3.1.1.

Author: Gabriel Moser

## Installation using Composer
composer update

## Run Application
composer serve

## Execute tests
"vendor/bin/phpunit" --testsuite Application

## Usage Example:

GET
http://localhost:8080/api/shout/Dalai Lama
http://localhost:8080/api/shout/Albert Einstein?limit=5
http://localhost:8080/api/shout/Albert Einstein?limit=1
