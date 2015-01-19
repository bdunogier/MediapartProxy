<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\SiteReaderProxyBundle\Proxy;

use BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator\CredentialsBased;
use BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator\FormBased;
use BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator\UrlBased;

abstract class AbstractFormBasedAuthenticator implements CredentialsBased, UrlBased, FormBased
{
    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var string */
    private $uri;

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
        $context = stream_context_create(
            array(
                'http' => array(
                    'method' => 'POST',
                    'header' => 'Content-type: application/x-www-form-urlencoded\r\n',
                    'content' => http_build_query(
                        ["name" => $this->getUsername(), "pass" => $this->getPassword()] + $this->getExtraFormFields()
                    )
                )
            )
        );

        file_get_contents( $this->getUri(), false, $context );
        foreach ($http_response_header as $headerString) {
            if (!strstr( ':', $headerString )) {
                continue;
            }

            list( $headerName, $headerValue ) = explode( ':', $headerString );

            if ($headerName !== 'Set-Cookie') {
                continue;
            }

            if (substr( $headerName, 0, 4 ) == 'SESS') {
                $sessionCookieString = $headerValue;
                continue;
            }

            if (substr( $headerName, 0, 19 ) == 'roles=authenticated') {
                $roleCookieString = $headerValue;
            }
        }

        if (!isset( $sessionCookieString ) || !isset( $roleCookieString )) {
            throw new \Exception( "No role cookie in loginresponse" );
        }

        return $sessionCookieString;
    }
}
