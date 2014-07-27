<?php
/**
 * This file is part of the eZ Publish Legacy package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributd with this source code.
 * @version //autogentag//
 */
namespace BD\MediapartProxyBundle\Proxy;


use BD\MediapartProxyBundle\Proxy\Exception\ProxyAuthenticationException;

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
