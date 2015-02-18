<?php
namespace BD\SiteReaderProxyBundle\Proxy\WebsiteBrowser;

interface SessionAware
{
    public function setSessionCookieString( $sessionCookieString );

    public function getSessionCookieString();
}
