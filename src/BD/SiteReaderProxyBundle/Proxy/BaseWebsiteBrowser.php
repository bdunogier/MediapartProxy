<?php
namespace BD\SiteReaderProxyBundle\Proxy;

use BD\SiteReaderProxyBundle\Proxy\Exception\ProxyAuthenticationException;
use BD\SiteReaderProxyBundle\Proxy\WebsiteBrowser;
use BD\SiteReaderProxyBundle\Proxy\WebsiteConfiguration;
use DOMDocument;
use DOMXPath;

class BaseWebsiteBrowser implements WebsiteBrowser, WebsiteBrowser\SessionAware
{
    /** @var \BD\SiteReaderProxyBundle\Proxy\WebsiteConfiguration */
    private $configuration;

    /** @var \BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator */
    private $authenticator;

    /** @var string */
    private $sessionCookieString;

    /**
     * @param \BD\SiteReaderProxyBundle\Proxy\WebsiteConfiguration $configuration
     * @param \BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator $authenticator
     */
    public function __construct( WebsiteConfiguration $configuration, WebsiteAuthenticator $authenticator )
    {
        $this->configuration = $configuration;
        $this->authenticator = $authenticator;
    }

    public function fetchRSS()
    {
        $rss = file_get_contents( $this->configuration->getRssUri() );

        $domDocument = new DOMDocument();
        $domDocument->loadXML( $rss );

        $xpath = new DOMXpath( $domDocument );
        /** @var \DOMNode $link */
        foreach ( $xpath->query( '/rss/channel/item/link' ) as $link )
        {
            $link->nodeValue = $this->configuration->fixupRssLink( $link->textContent );
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
            $html = $this->downloadArticleWithAuthentication( $uri );
        } catch (ProxyAuthenticationException $e) {
            $this->sessionCookieString = $this->authenticator->login();
            $html = $this->downloadArticleWithAuthentication( $uri );
        }
        return $html;
    }

    /**
     * Fetches the article at $uri with the given session cookie
     *
     * @param string $uri
     *
     * @throws \BD\SiteReaderProxyBundle\Proxy\Exception\ProxyAuthenticationException
     * @return string
     */
    private function downloadArticleWithAuthentication( $uri )
    {
        $curl = curl_init( $this->configuration->getBaseArticleUri() . '/' . ltrim( $uri, '/' ) );
        curl_setopt( $curl, CURLOPT_COOKIE, $this->sessionCookieString );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        $html = curl_exec( $curl );

        if ( $this->configuration->isDisconnected( $html ) )
        {
            throw new ProxyAuthenticationException( $uri );
        }

        return $html;
    }

    public function setSessionCookieString( $sessionCookieString )
    {
        $this->sessionCookieString = $sessionCookieString;
    }

    public function getSessionCookieString()
    {
        return $this->sessionCookieString;
    }
}
