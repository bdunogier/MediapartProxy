<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\SiteReaderProxy\MediapartSiteBundle;

use BD\SiteReaderProxyBundle\Proxy\AbstractFormBasedAuthenticator;
use BD\SiteReaderProxyBundle\Proxy\Exception\ProxyAuthenticationException;
use BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator;
use BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator\CookieBased;
use BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator\CredentialsBased;
use BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator\FormBased;
use BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator\UrlBased;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;

class MediapartAuthenticator
    extends AbstractFormBasedAuthenticator
    implements WebsiteAuthenticator, UrlBased, CredentialsBased, FormBased, CookieBased
{
    public function getUsernameFieldName()
    {
        return 'name';
    }

    public function getPasswordFieldName()
    {
        return 'pass';
    }

    public function getExtraFormFields()
    {
        return ["op" => "ok", "form_id" => "user_login_block"];
    }

    public function verifyCookies( CookieJar $cookieJar )
    {
        $gotSessionCookie = $gotRoleCookie = false;

        /** @var SetCookie $cookie */
        foreach ( $cookieJar as $cookie )
        {
            if ( substr( $cookie->getName(), 0, 4 ) == 'SESS' )
            {
                $gotSessionCookie = true;
                continue;
            }

            if ( $cookie->getName() === 'roles'  && $cookie->getValue() === 'authenticated+user%3A%3Aabonne' )
            {
                $gotRoleCookie = true;
            }
        }

        if ( !$gotRoleCookie || !$gotSessionCookie )
        {
            throw new ProxyAuthenticationException( $this->getUri() );
        }
    }
}
