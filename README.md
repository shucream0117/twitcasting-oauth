# TwitCastingOAuth

for [TwitCasting API v2](http://twitcasting.tv/indexapiv2.php)

## Requirement

- PHP 7.1.x

## Installation
via composer.

```console
$ php composer.phar require shucream0117/twitcasting-oauth
```

## Usage

### Setup Config File

```console
$ cp vendor/shucream0117/twitcasting-oauth/config/config.template path-to-your-config-dir/config.php
$ vi path-to-your-config-dir/config.php # put your ClientID and ClientSecret !!
```

### Examples

#### Get Confirmation Page Url

```php
$config = new Config('path-to-your-config-dir/config.php');
$csrfToken = 'hoge';
$url = (new AuthCodeGrant($config))->getConfirmPageUrl($csrfToken);
```

#### Get AccessToken

```php
// handle callback request
$config = new Config('path-to-your-config-dir/config.php');
$state = $_GET['state'] ?? null;
$code = $_GET['code'] ?? null;
$accessToken = $grant->requestAccessToken($code, new AppExecutor($config));
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
$config = new Config('path-to-your-config-dir/config.php');
$executor = new AppExecutor($config);
$response = $executor->get("users/twitcasting_jp");
$userInfo = json_decode($response->getBody()->getContents(), true);
```

## License
MIT
