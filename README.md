# Symfony Dependency Injection Configuration Bundle

This bundle makes it possible to easily customise existing service definitions
defined in third-party bundles.

**Note: Many bundles offer their own configuration options to customise how
their services are configured. Please refer to their documentation first for
available configuration options before using this bundle.**

## Installation

Use [Composer][1] to install the bundle:

```bash
$ composer require xabbuh/di-configuration-bundle
```

Then, enable it in your application:

```php
// app/AppKernel.php
// ...

public function registerBundles()
{
    $bundles = array(
        // ...
        new Xabbuh\DiConfigurationBundle\XabbuhDiConfigurationBundle(),
    );

    // ...

    return $bundles;
}
```

## Configuration

**Note**: You can only change the configuration of existing services. The bundle
is not meant to be used to configure new services. If you want to add your own
services use [the built-in Symfony configuration mechanisms][2].

### Customize Service Definitions

Change the class name of a service:

```yaml
xabbuh_di_configuration:
    logger:
        class: app.logger
```

If you do not want to configure any other options of a particular service, you
can omit the `class` key:

```yaml
xabbuh_di_configuration:
    monolog.logger: app.logger
```

You can replace existing arguments using the `arguments` key. A reference to
another service needs to be the service id prefixed with the `@` character:

```yaml
xabbuh_di_configuration:
    locale_listener:
        arguments:
            - de
            - @app.router
```

You can use the `index` key for an argument if you do not want to replace all
arguments, but want to skip some of them. The following example only replaces
the first and the third argument of the `locale_listener` service, but leaves
the second argument as is:

```yaml
xabbuh_di_configuration:
    locale_listener:
        arguments:
            - de
            - index: 2
              value: @app.request_stack
```

[1]: https://getcomposer.org/
[2]: https://symfony.com/doc/current/book/service_container.html
