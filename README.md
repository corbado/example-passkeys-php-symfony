# PHP Laravel Passkey Example App

This is a sample implementation of a PHP Laravel application that offers passkey authentication.
For simple passkey-first authentication, the Corbado UI components are used.

Please read the [full blog post](https://www.corbado.com/blog/passkeys-php-laravel) to understand all the required steps for a passkey integration into PHP Laravel apps.

## File structure

```
...
├── .env                              # Contains all environment variables
├── templates
|   ├── home
|   |   └── index.html.twig           # Homepage template
|   ├── profile
|   |   └── index.html.twig           # Profile page template
|   └── base.html.twig                # Layout for our pages
└── src
    └── Crontroller                        
        ├── ProfileController.php     # Responsible to retrieve profile information in backend
        └── HomeController.php        # Render's our homepage
```

## Prerequisites

Please follow the steps in [Getting started](https://docs.corbado.com/overview/getting-started) to create and configure
a project in the [Corbado developer panel](https://app.corbado.com/signin#register).

Create a .env file with the contents of the .env.example file and paste your own project ID as well as your own API secret.
Make sure to copy all other contents from the .env.example file as well.

Also make sure that you have [PHP](https://php.net) as well as [Composer](https://getcomposer.org/) and the [Symfony CLI](https://symfony.com/download) installed and accessible from your shell.

## Usage

Then you can run the project locally by first downloading all dependencies with `composer install`
and start the local instance with `php artisan serve`.

Now head  to [http://localhost:8000](http://localhost:8000) in your browser to see the page.
