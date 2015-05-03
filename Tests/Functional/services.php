<?php

class AppLogger
{
}

class CustomLogger
{
}

class CustomRequestStack
{
}

class LocaleListener
{
    public $defaultLocale;
    public $router;
    public $requestStack;

    public function __construct($defaultLocale, $router, $requestStack)
    {
        $this->defaultLocale = $defaultLocale;
        $this->router = $router;
        $this->requestStack = $requestStack;
    }
}

class Router
{
}

class RequestStack
{
}
