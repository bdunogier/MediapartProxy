<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\SiteReaderProxyBundle\Proxy;

use BD\SiteReaderProxyBundle\Proxy\Exception\ProxyAuthenticationException;
use BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator\CredentialsBased;
use BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator\FormBased;
use BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator\UrlBased;
use GuzzleHttp\Client;

abstract class AbstractFormBasedAuthenticator implements CredentialsBased, UrlBased, FormBased
{
    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var string */
    private $uri;

    /** @var \GuzzleHttp\Client */
    protected $client;

    public function setClient( Client $client )
    {
        $this->client = $client;
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

        $response = $this->client->post(
            $this->getUri(),
            ['body' => $postFields, 'allow_redirects' => false]
        );

        if ( !$response->hasHeader( 'location' ) )
        {
            throw new \Exception( "no location in response" );
        }
        $response = $this->client->get( $response->getHeader( 'location' ), ['allow_redirects' => false] );
        if ( !$response->hasHeader( 'set-cookie' ) )
        {
            throw new \Exception( "no location in 2nd response" );
        }

        $sessionCookieString = $this->verifyHeaders( $response->getHeaders() );

        if ( ( $sessionCookieString = $this->verifyHeaders( $response->getHeaders() ) ) === null )
        {
            throw new ProxyAuthenticationException( $this->getUri() );
        }
        return $sessionCookieString;
    }
}
