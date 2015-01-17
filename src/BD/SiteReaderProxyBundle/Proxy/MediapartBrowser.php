<?php
/**
 * This file is part of the eZ Publish Legacy package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributd with this source code.
 * @version //autogentag//
 */
namespace BD\SiteReaderProxyBundle\Proxy;

use BD\SiteReaderProxyBundle\Proxy\Exception\ProxyAuthenticationException;
use DOMDocument;
use DOMXPath;

class MediapartBrowser implements WebsiteBrowser
{
    private $appUri;

    private $rssUri = 'http://www.mediapart.fr/articles/feed';

    private $loginUri = 'http://www.mediapart.fr/?destination=node';

    private $baseArticlesUri = 'http://www.mediapart.fr/journal/';

    private $sessionCookieString;

    /**
     * @var
     */
    private $username;

    /**
     * @var
     */
    private $password;

    public function __construct( $username, $password, $appUri, $sessionCookieString )
    {
        $this->username = $username;
        $this->password = $password;
        $this->appUri = $appUri;
        $this->sessionCookieString = $sessionCookieString;
    }

    public function fetchRSS()
    {
        $rss = file_get_contents( $this->rssUri );

        $domDocument = new DOMDocument();
        $domDocument->loadXML( $rss );

        $xpath = new DOMXpath( $domDocument );
        /** @var \DOMNode $link */
        foreach ( $xpath->query( '/rss/channel/item/link' ) as $link )
        {
            $link->nodeValue = $this->fixupLink( $link->textContent );
        }

        return $domDocument->saveXML();
    }

    /**
     * Fetches the article at $uri with credentials
     *
     * @param string $uri
     *
     * @return string
     * @throws \BD\SiteReaderProxyBundle\Proxy\Exception\ProxyAuthenticationException if authentication failed on the remote server
     */
    public function fetchArticle( $uri )
    {
        try {
            $articleHTML = $this->downloadArticleWithAuthentication($uri, $this->sessionCookieString);
        } catch (ProxyAuthenticationException $e) {
            $this->login();
            $articleHTML = $this->downloadArticleWithAuthentication($uri, $this->sessionCookieString);
        }
        return $articleHTML;
    }

    /**
     * Fetches the article at $uri with the given session cookie
     *
     * @param string $uri
     * @param string $sessionCookieString
     *
     * @throws Exception\ProxyAuthenticationException
     * @return string
     */
    private function downloadArticleWithAuthentication( $uri, $sessionCookieString )
    {
        $curl = curl_init( $this->baseArticlesUri . $uri );
        curl_setopt( $curl, CURLOPT_COOKIE, $sessionCookieString );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        $html = curl_exec( $curl );

        if ( strstr( $html, 'disconnected_link') )
        {
            throw new ProxyAuthenticationException( $uri );
        }

        return $html;
    }

    private function login()
    {
        $context = stream_context_create(
            array(
                'http' => array(
                    'method' => 'POST',
                    'header'  => 'Content-type: application/x-www-form-urlencoded\r\n',
                    'content' => http_build_query(
                        array(
                            "name" => $this->username,
                            "pass" => $this->password,
                            "op" => "ok",
                            "form_id" => "user_login_block"
                        )
                    )
                )
            )
        );

        file_get_contents($this->loginUri, false, $context);
         foreach( $http_response_header as $headerString) {
            if (!strstr(':', $headerString)) continue;

            list($headerName, $headerValue) = explode(':', $headerString);

            if ($headerName !== 'Set-Cookie') continue;

            if (substr($headerName, 0, 4) == 'SESS') {
                $sessionCookieString = $headerValue;
                continue;
            }

            if ( substr($headerName, 0, 19) == 'roles=authenticated') {
                $roleCookieString = $headerValue;
            }
        }

        if (!isset($sessionCookieString) || !isset($roleCookieString)) {
            throw new \Exception( "No role cookie in loginresponse");
        }
    }

    private function fixupLink( $link )
    {
        $link = str_replace( 'http://www.mediapart.fr/', $this->appUri, $link );
        $link .= '?onglet=full';
        return $link;
    }
}
