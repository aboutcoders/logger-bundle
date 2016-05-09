AbcLoggerBundle
===============

A symfony bundle that provides a REST-API to log messages from external client applications.

Build Status: [![Build Status](https://travis-ci.org/aboutcoders/logger-bundle.svg?branch=master)](https://travis-ci.org/aboutcoders/logger-bundle)

## Installation

Follow the installation instructions of the required third party bundles:
* [SensioFrameworkExtraBundle](http://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/index.html)
* [NelmioApiDocBundle](https://github.com/nelmio/NelmioApiDocBundle)
* [FOSRestBundle](https://github.com/FriendsOfSymfony/FOSRestBundle)

Add the AbcLoggerBundle to your `composer.json` file

```json
{
    "require": {
        "aboutcoders/logger-bundle": "~1.0"
    }
}
```

Include the bundle in the AppKernel.php class

```php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Abc\Bundle\LoggerBundle\AbcLoggerBundle(),
    );

    return $bundles;
}
```

Import the routing files

```yaml
abc-rest-logger:
    type: rest
    resource: "@AbcLoggerBundle/Resources/config/routing/rest.yml"
    prefix: /api
```

## Configuration

All you need to do is define the names of the applications which are allowed to log and configure the Monolog logging channel to use for each of them. Please refer to the [offical symfony documentation](http://symfony.com/doc/current/cookbook/logging/channels_handlers.html) on how-to define custom channels or log to different files.

```yaml
abc_logger:
    applications:
        my_application:
            channel: my_channel
```

## Usage

With the above configuration example you can now post log entries to the following url: [http://localhost/api/log/my_application](http://localhost/api/log/my_application)

The request body must contain the following parameters:

* `level`: The log level [emergency|alert|critical|error|warning|notice|info|debug]
* `message`: The log message
* `context`: Optional, an array of context data as defined by [Monolog](https://github.com/Seldaek/monolog)

Please refer to the API documentation that can be generated with the [NelmioApiDocBundle](https://github.com/nelmio/NelmioApiDocBundle) to get more detailed information about the API method.