# sfMandrillPlugin
Easy to use plugin to handle sending mail through Mandrill

# installation

* Install the plugin with composer:

```bash
$ composer require stadline/sf-mandrill-plugin
```
* Edit ProjectConfiguration.class.php to activate the plugin:
```php
$this->enablePlugins(array(
    ...
    'sfMandrillPlugin',
));
```
* Configuration:
Update your app.yml with:

```
    #Update configuration for hip_mandrill
    mandrill:
        api_key: #Add your mandrill key
        from_email: #Add your sender email
        from_name: #Add your sender name
```

* Development:
This plugin handles `single_address` strategy of symfony mailer.
(see http://symfony.com/legacy/doc/more-with-symfony/1_4/en/04-Emails#chapter_04_the_delivery_strategy)

If symfony mailer is configured with single_address strategy, all emails sent by this plugin will be redirected to the address configured via the delivery_address setting.