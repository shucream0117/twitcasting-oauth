<?php

namespace Shucream0117\TwitCastingOAuth\Tests\GrantFlow;

use Shucream0117\TwitCastingOAuth\Utils\Config;
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

        $configMock = $this->getMockBuilder(Config::class)->getMock();
        $configMock->expects($this->at(0))
            ->method('get')
            ->willReturn($dummyClientId);

        $configMock->expects($this->at(1))
            ->method('get')
            ->willReturn($dummyClientSecret);

        $configMock->expects($this->at(2))
            ->method('get')
            ->willReturn($dummyCallbackUrl);

        $grant = new AuthCodeGrant($configMock);
        $actual = $grant->getConfirmPageUrl($dummyCsrfToken);
        $this->assertContains("client_id={$dummyClientId}", $actual);
        $this->assertContains("state={$dummyCsrfToken}", $actual);
        $this->assertContains('response_type=code', $actual);
    }
}
