<?php

namespace Shucream0117\TwitCastingOAuth\GrantFlow;

interface AuthorizeFlowInterface
{
    /**
     * Get an url of the page where users allow or deny to grant permission to your app.
     * @return string
     */
    public function getConfirmPageUrl(): string;
}
