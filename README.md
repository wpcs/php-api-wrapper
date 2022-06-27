# php-api-wrapper

A PHP API wrapper to easily communicate with the WPCS.io API.

Example usage:

```php
$exportPath = './archive.tgz';
$request = new \WPCS\API\CreateVersionRequest();
$response = $request
    ->setRegion('us1')
    ->setApiKey('an-api-key')
    ->setApiSecret('an-api-secret')
    ->setName('v1.0.0')
    ->setWordPressVersion('6.0')
    ->setPhpVersion('7.4')
    ->setSnapshotPath($exportPath)
    ->send();
```
