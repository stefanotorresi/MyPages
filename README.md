MyPages
===
[![Latest Stable Version](https://poser.pugx.org/stefanotorresi/my-pages/v/stable.png)](https://packagist.org/packages/stefanotorresi/my-pages)
[![Latest Unstable Version](https://poser.pugx.org/stefanotorresi/my-pages/v/unstable.png)](https://packagist.org/packages/stefanotorresi/my-pages)
[![Build Status](https://travis-ci.org/stefanotorresi/MyPages.png?branch=master)](https://travis-ci.org/stefanotorresi/MyPages)
[![Code Coverage](https://scrutinizer-ci.com/g/stefanotorresi/MyPages/badges/coverage.png?s=79baa286714a1b66bf26fae10608d4ef1dc76b73)](https://scrutinizer-ci.com/g/stefanotorresi/MyPages/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/stefanotorresi/MyPages/badges/quality-score.png?s=f16ed69ccbfc741b6fc09d2637acf01b5b423d19)](https://scrutinizer-ci.com/g/stefanotorresi/MyPages/)

MyPages is a very simple Zend Framework 2 module, providing a basic Controller that resolves view templates from the route.

Usage
---

  1. Put your view templates inside the `pages` sub directory of a path registered as a `template_path_stack` with the [`ViewManager`](http://framework.zend.com/manual/2.2/en/modules/zend.view.quick-start.html#configuration).
  2. Add your routes specifying `MyPages\PageController` as the controller and the template name as the `page` param:
```php
   // somewhere in your router config
   'static-page-route' => [
       'type' => 'literal',
       'options' => [
           'route' => '/static-page'
           'defaults' => [
               'controller' => 'MyPages\PageController',
               'page' => 'static-page-template',
           ],
       ],
   ]
```
this will render the first resolved `pages/static-page-template` view when the route matches `/static-page`.
Of course, you can use any other resolver config that works for you.

There are two settings you can change:
```php
// somewhere in your autoloaded configs
'MyPages' => [
    'route_param_name' => 'page',
    'template_dir' => 'pages',
],
```

Credits
---

The module is just a brutal rip-off of the [Zend Framework web site PageController] module,
written by [Matthew Weier O'Phinney]. All credits go to him.

For a more complete module with functionalities like caching, check out [Matthew Weier O'Phinney]'s [PhlySimplePage module].

[Zend Framework web site PageController]: //github.com/zendframework/zf-web/tree/master/module/PageController
[Matthew Weier O'Phinney]: http://mwop.net
[PhlySimplePage module]: //github.com/weierophinney/PhlySimplePage/
