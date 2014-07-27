<?php

namespace BD\MediapartProxyBundle\Controller;

use BD\MediapartProxyBundle\Proxy\WebsiteBrowser;
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
        return new Response( $this->browser->fetchArticle( $uri ) );
    }
}
