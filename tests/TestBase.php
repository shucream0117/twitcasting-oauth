<?php

namespace Shucream0117\TwitCastingOAuth\Tests;

use PHPUnit\Framework\TestCase;
use Shucream0117\TwitCastingOAuth\Config;

abstract class TestBase extends TestCase
{
    /**
     * @return Config
     */
    protected static function getTestConfig(): Config
    {
        return new Config(__DIR__ . '/../config/config.test.php');
    }
}
