<?php

namespace Shucream0117\TwitCastingOAuth\Tests\GrantFlow;

use Shucream0117\TwitCastingOAuth\GrantFlow\AuthCodeGrant;
use Shucream0117\TwitCastingOAuth\Tests\TestBase;

class AuthCodeGrantTest extends TestBase
{
    /**
     * @covers AuthCodeGrant::getConfirmPageUrl()
     */
    public function testGetConfirmPageUrl()
    {
        $config = static::getTestConfig();
        $dummyClientId = $config->get('client_id');
        $dummyCsrfToken = uniqid();

        $grant = new AuthCodeGrant($config);
        $actual = $grant->getConfirmPageUrl($dummyCsrfToken);
        $this->assertContains("client_id={$dummyClientId}", $actual);
        $this->assertContains("state={$dummyCsrfToken}", $actual);
        $this->assertContains('response_type=code', $actual);
    }
}
