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
        return 'password';
    }

    public function getExtraFormFields()
    {
        return ["op" => "ok"];
    }

    public function verifyCookies( CookieJar $cookieJar )
    {
        /** @var SetCookie $cookie */
        foreach ( $cookieJar as $cookie )
        {
            if ( $cookie->getName() == 'MPSESSID' )
            {
                return true;
            }
        }

        throw new ProxyAuthenticationException( $this->getUri() );
    }
}
