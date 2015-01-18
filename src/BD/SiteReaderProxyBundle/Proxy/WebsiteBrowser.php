<?php
namespace BD\SiteReaderProxyBundle\Proxy;


use BD\SiteReaderProxyBundle\Proxy\Exception\ProxyAuthenticationException;

interface WebsiteBrowser {

    public function fetchRSS();

    /**
     * Fetches the article at $uri with credentials
     * @param string $uri
     * @return string
     * @throws ProxyAuthenticationException if authentication failed on the remote server
     */
    public function fetchArticle( $uri );
}
