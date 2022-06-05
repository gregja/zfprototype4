# ZendX

Install
-------
```cmd
$ composer require pedro151/zendx
```

setting is in `public\index.php`
```php
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../vendor/zendframework/zendframework1/library'),
    realpath(APPLICATION_PATH . '/../vendor/pedro151/zendx/library'),
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));
```

Configuration
-------------

setting is in `application/configs/application.ini` 

```ini
;CONFIG ZENDX
resources.view.helperPath.ZendX_JQuery_View_Helper = APPLICATION_PATH "/../ZendX/JQuery/View/Helper"
```

or in `application/Bootstrap.php`

```php
$view->addHelperPath ( "ZendX/JQuery/View/Helper" , "ZendX_JQuery_View_Helper" );
```


Enabling
--------

In `Bootstrap.php` File setting:

```php
public function _initJQuery ()
{
    $this->_bootstrap ( 'FrontController' );
    $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper ( 'viewRenderer' );
    if ( null === $viewRenderer->view )
    {
        $viewRenderer->initView ();
    }
    $view = $viewRenderer->view;

    /* Initialize action controller here */
    $view->jQuery ()
         ->uiDisable ()
         ->enable ();
}
```
