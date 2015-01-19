<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\SiteReaderProxy\LmdSiteBundle;

use BD\SiteReaderProxyBundle\Proxy\WebsiteConfiguration;

class LmdConfiguration implements WebsiteConfiguration
{

    public function getRssUri()
    {
        return 'http://www.monde-diplomatique.fr/rss/';
    }

    public function getBaseArticleUri()
    {
        return 'http://www.monde-diplomatique.fr/';
    }

    /**
     * @param string $html
     *
     * @return bool
     */
    public function isDisconnected( $html )
    {
        return strpos( $html, 'href="/mon_compte"' ) !== 0;
    }

    /**
     * Fixes up links in the RSS to the app's
     *
     * @param string $rssLink
     *
     * @return string
     */
    public function fixUpRssLink( $rssLink )
    {
        return $rssLink;
    }
}
