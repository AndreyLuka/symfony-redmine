# Symfony Redmine Application

## Features

1. Show a list of projects.
2. Show a list of issues per project.
3. Track time per project, or per issue. This should happen within Redmine.
4. Comment on projects. This should happen within the Symfony site. It should not post these comments back to Redmine, rather have a database that stores and shows them.

## Requirements

* [PHP](https://php.net/) >= 7.1.3
* [Composer](https://getcomposer.org/)

## Installation

1. Copy files to a new project directory.
2. Copy `/.env.dist` to `/.env` and edit variables (database connection, etc).
3. Run `composer install`
4. Run `php bin/console doctrine:database:create`
5. Run `php bin/console doctrine:migrations:migrate`

## Default Users

1. admin:admin
2. user:user
