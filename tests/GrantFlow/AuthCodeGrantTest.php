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
        $dummyClientId = uniqid();
        $dummyCsrfToken = uniqid();
        $dummyClientSecret = uniqid();
        $dummyCallbackUrl = 'http://dummy-callback-url.com';

        $grant = new AuthCodeGrant($dummyClientId, $dummyClientSecret, $dummyCallbackUrl);
        $actual = $grant->getConfirmPageUrl($dummyCsrfToken);
        $this->assertContains("client_id={$dummyClientId}", $actual);
        $this->assertContains("state={$dummyCsrfToken}", $actual);
        $this->assertContains('response_type=code', $actual);
    }
}
