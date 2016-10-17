Yii2 component for use sms service by "Mobizone" API
=======================================================

INSTALLATION
------------

Run command
```php
    composer require black-lamp/blcms-sms-mobizone
```
or add
```php
    "black-lamp/blcms-sms-mobizone": "*",
```

### Configure component in your app
Example: 
```php
    'components' => [
        'sms' => [
                    'class' => 'bl\cms\sms\SmsMobizoneComponent',
        
                    'apiToken' => '00000000000000000000000000000000000000000',
                    'recipientPhoneNumber' => '380965550000',
                    'defaultMessage' => 'Default message',
                    'alphaName' => 'Name'
            ],
        ]
```

For get API token you must register account at https://mobizon.net.ua.
After this turn on API access on "API settings" panel section (https://mobizon.net.ua/panel) and get your token.

Recipien phone number must be in international format without plus.
Example: "380965550000".

Alpha name is your signature in SMS that will be used instead sender phone number. 
For use this property you must create alpha-name in your account (https://mobizon.net.ua/panel) on "My signatures" panel section. 
This property can be empty.

USING
------
For sending one SMS:
```php
    $messageId = Yii::$app->sms->send($recipient, $text, $alphaName);
```

For get user account balance:
```php
    $balance = Yii::$app->sms->getBalance();
```