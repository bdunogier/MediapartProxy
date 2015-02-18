<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\SiteReaderProxyBundle\Proxy;


interface WebsiteAuthenticator
{
    /**
     * Logs the configured user, and returns the session cookie string
     */
    public function login();

    public function setCredentials( $username, $password );
}
