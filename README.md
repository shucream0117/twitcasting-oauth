# TwitCastingOAuth

for [TwitCasting API v2](http://twitcasting.tv/indexapiv2.php)

official document => http://apiv2-doc.twitcasting.tv/

## Requirement

- PHP 7.1.x

## Installation
via composer.

```console
$ curl -sS https://getcomposer.org/installer | php
$ php composer.phar require shucream0117/twitcasting-oauth:dev-master
```

## Usage

### Examples

#### Get Confirmation Page Url

```php
$csrfToken = 'csrf-token';
$url = (new AuthCodeGrant('your-client-id', 'your-secret-key', 'your-callback-url'))->getConfirmPageUrl($csrfToken);
```

#### Get AccessToken

```php
// handle callback request

$state = $_GET['state'] ?? null; // this should be same as CSRF token you set to AuthCodeGrant::getConfirmPageUrl() 
$code = $_GET['code'] ?? null; // handle error if $code is null
$accessToken = $grant->requestAccessToken($code, new AppExecutor('your-client-id', 'your-secret-key'));
```

#### Request as an user

```php
// GET /verify_credentials
$accessTokenEntity = new AccessToken('access-token');
$executor = new UserExecutor($accessTokenEntity);
$response = $executor->get("verify_credentials");
$userInfo = json_decode($response->getBody()->getContents(), true);

// POST /movies/:movie_id/comments
$movieId = 1234;
$response = $executor->post("movies/{$movieId}/comments", ['comment' => 'hello!!']);
```

#### Request as an application

```php
// GET /users/twitcasting/jp

$executor = new AppExecutor('your-client-id', 'your-secret-key');
$response = $executor->get("users/twitcasting_jp");
$userInfo = json_decode($response->getBody()->getContents(), true);
```

## Testing

```console
$ vendor/bin/phpunit --bootstrap tests/bootstrap.php tests/
```

## License
MIT
