<?php
namespace BD\SiteReaderProxy\MediapartSiteBundle;

use BD\SiteReaderProxyBundle\Proxy\WebsiteConfiguration;

class MediapartConfiguration implements WebsiteConfiguration
{
    private $appUri;

    private $rssUri = 'https://www.mediapart.fr/articles/feed';

    private $baseArticlesUri = 'https://www.mediapart.fr';

    /**
     * @param $username
     * @param $password
     * @param $appUri
     */
    public function __construct( $appUri )
    {
        $this->appUri = $appUri;
    }

    public function fixupRssLink( $rssLink )
    {
        $link = str_replace( 'http://www.mediapart.fr/', $this->appUri, $rssLink );
        $link .= '?onglet=full';
        return $link;
    }

    public function getRssUri()
    {
        return $this->rssUri;
    }

    public function getBaseArticleUri()
    {
        return $this->baseArticlesUri;
    }

    /**
     * @param string $html
     *
     * @return bool
     */
    public function isDisconnected( $html )
    {
        return strstr( $html, 'reserved-content' );
    }
}
