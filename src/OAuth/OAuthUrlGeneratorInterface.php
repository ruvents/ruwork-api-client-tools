<?php

namespace Ruwork\ApiClientTools\OAuth;

interface OAuthUrlGeneratorInterface
{
    /**
     * @param string $redirectUrl
     *
     * @return string
     */
    public function generateUrl($redirectUrl);
}
