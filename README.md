# WordPress Notifier - WP Notice Helper

WordPress Notifier is an object oriented helper library for handling WordPress admin notices inside a plugin or theme.
It supports persistent notices and dismissible notices out of the box and is extendable.

## Installation

### Composer

```bash
composer require pxlrbt/wordpress-notifier
```

### Manually
Download and extract the library inside your project. Then include the `bootstrap.php` file.

```php
require_once '[PATH_TO_FILE]/bootstrap.php';
```

## Usage

### Basic Usage

Create a new Notifier instance globally. This __must__ be called on every page load since it automatically registers hooks for printing the admin notices. Afterwards you can create simple noticies by using Notifiers static methods.

```php
Notifier::getInstance();

Notifier::info('An update is available.');
Notifier::error('Oops, something went wrong!');
```

### Advanced Usge

Again create a Notifier instance globally. Afterwards you can dispatch new notifications via your Notifier instance. Notification methods are chainable for more readable configuration.

```php
// Default method
$notifier = new Notifier();
// Singleton method
$notifier = Notifier::getInstance();
$notifier->dispatch(
    Notification::make('Configuration failed.')
        ->id('plugin_xy.config.failed')
        ->type('error')
        ->dismissible(true)
        ->persistent(true);
)
```
