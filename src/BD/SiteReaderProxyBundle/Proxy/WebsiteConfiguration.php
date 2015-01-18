<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\SiteReaderProxyBundle\Proxy;

interface WebsiteConfiguration
{
    public function getRssUri();

    public function getBaseArticleUri();

    /**
     * @param string $html
     *
     * @return bool
     */
    public function isDisconnected( $html );

    /**
     * Fixes up links in the RSS to the app's
     * @param string $rssLink
     *
     * @return string
     */
    public function fixUpRssLink( $rssLink );
}
