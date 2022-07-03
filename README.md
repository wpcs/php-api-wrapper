# WPCS PHP API wrapper

A PHP API wrapper to easily communicate with the WPCS.io API.

## Authentication

In order to use the package, you need to authenticate with the WPCS API. You can create an API key/secret pair in the console to use for this wrapper.

There are two ways to authenticate the requests with this package: using constants or the builder functions.

### Configuring authentication with builder functions

There are three functions to use to authenticate your requests: `setRegion(string $region)`, `setApiKey(string $key)` and `setApiSecret(string $secret)`

Example:

```php
$request = new \WPCS\API\GetTenantsRequest();
$response = $request
    ->setRegion('us1') // Or eu1, depending on your region.
    ->setApiKey('an-api-key') // The API Key you retrieved from the console
    ->setApiSecret('an-api-secret') // The API Secret you retrieved from the console
    ->send();
```

This allows you to use your own secrets management.

### Constants

If you are using, for example, WordPress, it is common to place secrets in the wp-config.php file as constants. You can do this for the wrapper as well. The three required constants are `WPCS_API_REGION`, `WPCS_API_KEY` and `WPCS_API_SECRET`. When these are defined, you do not need to use the builder functions to setup authentication.

Example:
```php
define('WPCS_API_REGION', 'us1'); // Or eu1, depending on your region.
define('WPCS_API_KEY', 'an-api-key'); // The API Key you retrieved from the console
define('WPCS_API_SECRET', 'an-api-secret'); // The API Secret you retrieved from the console

// No need to set the region, API key and secret using functions now
$request = new \WPCS\API\GetTenantsRequest();
$response = $request->send();
```

## Example usage

A request to the WPCS API to create a new version might look like this (when not using the constants to authenticate). This request will create a version, based on the last snapshot of the current production version in WPCS in a product that resides in the `US1 region`.

```php
$request = new \WPCS\API\CreateVersionRequest();
$response = $request
    ->setRegion('us1')
    ->setApiKey('an-api-key')
    ->setApiSecret('an-api-secret')
    ->setName('v2.0.0')
    ->setWordPressVersion('6.0')
    ->setPhpVersion('7.4')
    ->send();
```
