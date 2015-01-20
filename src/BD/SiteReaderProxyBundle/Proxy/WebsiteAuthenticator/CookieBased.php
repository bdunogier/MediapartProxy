<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator;

use BD\SiteReaderProxyBundle\Proxy\Exception\ProxyAuthenticationException;
use GuzzleHttp\Cookie\CookieJar;

interface CookieBased
{
    /**
     * Verifies the contents of the cookie jar after login
     *
     * @param \GuzzleHttp\Cookie\CookieJar $cookieJar
     *
     * @throws ProxyAuthenticationException If login failed
     */
    public function verifyCookies( CookieJar $cookieJar );

    public function getCookieJar();
}
