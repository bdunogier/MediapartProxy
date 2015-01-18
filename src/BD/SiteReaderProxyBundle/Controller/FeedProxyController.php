<?php

namespace BD\SiteReaderProxyBundle\Controller;

use BD\SiteReaderProxyBundle\Proxy\WebsiteBrowser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class FeedProxyController extends Controller
{
    /** @var WebsiteBrowser */
    private $browser;

    public function __construct( WebsiteBrowser $browser )
    {
        $this->browser = $browser;
    }

    public function proxyArticlesFeedAction()
    {
        return new Response(
            $this->browser->fetchRSS(),
            200,
            array( 'Content-Type' => 'application/rss+xml; charset=utf-8' )
        );
    }

    public function proxyArticleAction( $uri )
    {
        // we want full articles !
        $uri = $uri . '?onglet=full';
        return new Response( $this->browser->fetchArticle( $uri ) );
    }
}
