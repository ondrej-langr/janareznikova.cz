# Sage + Vite Theme

Sage is a WordPress starter theme with a modern development workflow.

## Features

* [Vite](https://vitejs.dev) tooling with support for TypeScript, React, and Vue, and Hot Module Replacement (HMR) out-of-the-box.
* Scss for stylesheets
* Image lazy loading.
* Basic styles
* Modern JavaScript
* [Blade](https://laravel.com/docs/5.6/blade) as a templating engine
* [Controller](https://github.com/soberwp/controller) for passing data to Blade templates
* No IE Support! Don't want it - not adding it.

## Requirements

Make sure all dependencies have been installed before moving on:

* [WordPress](https://wordpress.org/) >= 4.7
* [PHP](https://secure.php.net/manual/en/install.php) >= 7.1.3 (with [`php-mbstring`](https://secure.php.net/manual/en/book.mbstring.php) enabled)
* [Composer](https://getcomposer.org/download/)
* [Node.js](http://nodejs.org/) >= 8.0.0
* [Yarn](https://yarnpkg.com/en/docs/install)

## Theme installation

Install Sage using Composer from your WordPress themes directory (replace `your-theme-name` below with the name of your theme):

```shell
# @ app/themes/ or wp-content/themes/
$ composer create-project 8bit-echo/sage your-theme-name
```

To install the latest development version of Sage, add `dev-master` to the end of the command:

```shell
$ composer create-project 8bit-echo/sage your-theme-name dev-master
```

## Theme structure

```shell
themes/your-theme-name/         # → Root of your Sage based theme
├── app/                        # → Theme PHP
│   ├── Controllers/            # → Controller files
│   ├── Classes/                # → Other PHP files
│   ├── admin.php               # → Theme customizer setup
│   ├── filters.php             # → Theme filters
│   ├── helpers.php             # → Helper functions
│   └── setup.php               # → Theme setup
├── composer.json               # → Autoloading for `app/` files
├── composer.lock               # → Composer lock file (never edit)
├── dist/                       # → Built theme assets (never edit)
├── node_modules/               # → Node.js packages (never edit)
├── package.json                # → Node.js dependencies and scripts
├── resources/                  # → Theme assets and templates
│   ├── assets/                 # → Front-end assets
│   │   ├── fonts/              # → Theme fonts
│   │   ├── images/             # → Theme images
│   │   ├── scripts/            # → Theme JS
│   │   └── styles/             # → Theme stylesheets
│   ├── functions.php           # → Composer autoloader, theme includes
│   ├── functions-default.php   # → Opinionated default functions.
│   ├── functions-app.php       # → Write all your custom functionality here.
│   ├── index.php               # → Never manually edit
│   ├── screenshot.png          # → Theme screenshot for WP admin
│   ├── style.css               # → Theme meta information
│   └── views/                  # → Theme templates
│       ├── layouts/            # → Base templates
│       └── partials/           # → Partial templates
└── vendor/                     # → Composer packages (never edit)
```

## Theme setup

Edit `app/setup.php` to enable or disable theme features, setup navigation menus, post thumbnail sizes, and sidebars.

Rename `.env.example` to `.env` and choose one of the preset values for `APP_ENV`.

**NOTE: THIS VALUE WILL CHANGE AUTOMATICALLY WHEN YOU RUN THE YARN SCRIPTS BELOW.**

## Theme Development

* Run `yarn` from the theme directory to install dependencies
* By default all top-level files in the `scripts` directory will be used as entry points for the build process.
* to register a script on a page, go to the Page's Controller and in the `__before` method, write
```php
Vite::register('scriptName.ts');
 ```
 This line is what enables HMR to work during development and the production scripts to be registered directly in production.

### Build commands

* `yarn dev` — Compile assets when file changes are made with HMR.
* `yarn build` — Compile and optimize the files in your assets directory

## Documentation

* [Sage documentation](https://roots.io/sage/docs/)
* [Controller documentation](https://github.com/soberwp/controller#usage)
* [Vite Documentation](https://vitejs.dev/guide/)
