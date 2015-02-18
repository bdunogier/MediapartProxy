<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\SiteReaderProxyBundle\Proxy;

use BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator\CookieBased;
use BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator\CredentialsBased;
use BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator\FormBased;
use BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator\UrlBased;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

abstract class AbstractFormBasedAuthenticator implements CredentialsBased, UrlBased, FormBased, CookieBased
{
    /** @var CookieJar */
    private $cookieJar;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var string */
    private $uri;

    /** @var \GuzzleHttp\Client */
    protected $guzzle;

    public function setGuzzleClient( Client $guzzle )
    {
        $this->guzzle = $guzzle;
    }

    public function setCredentials( $username, $password )
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setUri( $uri )
    {
        $this->uri = $uri;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function login()
    {
        $postFields = [
            $this->getUsernameFieldName() => $this->getUsername(),
            $this->getPasswordFieldName() => $this->getPassword()
        ] + $this->getExtraFormFields();

        $this->guzzle->post(
            $this->getUri(),
            ['body' => $postFields, 'allow_redirects' => true, 'cookies' => $this->getCookieJar() ]
        );

        $this->verifyCookies( $this->getCookieJar() );
    }

    /**
     * @return CookieJar
     */
    public function getCookieJar()
    {
        if ( !isset( $this->cookieJar ) )
        {
            $this->cookieJar = new CookieJar();
        }
        return $this->cookieJar;
    }

    /**
     * @param CookieJar $cookieJar
     */
    public function setCookieJar( $cookieJar )
    {
        $this->cookieJar = $cookieJar;
    }
}
