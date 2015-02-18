<?php
namespace BD\SiteReaderProxy\LmdSiteBundle\Controller;

use BD\SiteReaderProxyBundle\Proxy\WebsiteBrowser;
use Symfony\Component\HttpFoundation\Response;

class LmdSiteController
{
    /**
     * @var \BD\SiteReaderProxyBundle\Proxy\WebsiteBrowser
     */
    private $browser;

    /**
     * @param \BD\SiteReaderProxyBundle\Proxy\WebsiteBrowser $browser
     */
    public function __construct( WebsiteBrowser $browser )
    {
        $this->browser = $browser;
    }

    public function uriAction( $uri )
    {
        return new Response( $this->browser->fetchArticle( $uri ) );
    }

    public function rssAction()
    {
        return new Response(
            $this->browser->fetchRSS(),
            200,
            array( 'Content-Type' => 'application/rss+xml; charset=utf-8' )
        );
    }

}
