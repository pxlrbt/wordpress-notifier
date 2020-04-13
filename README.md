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

### Create a Notifier

Create a new Notifier instance globally. This __must__ be called on every page load since it automatically registers hooks for printing the admin notices. Afterwards you can create simple noticies by using Notifiers static methods.

```php
Notifier::getInstance();
#or
$notifier = new Notifier()
```

### Create a notification

After creating a Notifier instance you can dispatch new notifications via it. Either use Notifiers static functions for simple notifications or create a notification object yourself and configure it with it's chainable methods then dispatch it via the Notifier.

```php
// Static functions refer to last Notifier created
Notifier::info('An update is available.');
Notifier::error('Oops, something went wrong!');

// Advanced usage
$notifier->dispatch(
    Notification::create('Plugin configuration is missing!')
        ->id('plugin_xy.config.failed')
        ->type('error')
        ->dismissible(true)
        ->persistent(true);
)

```
