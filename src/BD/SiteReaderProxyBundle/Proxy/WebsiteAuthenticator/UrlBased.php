<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator;


interface UrlBased
{
    public function setUri( $uri );

    public function getUri();
}
