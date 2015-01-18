<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\SiteReaderProxy\MediapartSiteBundle;

use BD\SiteReaderProxyBundle\Proxy\AbstractFormBasedAuthenticator;
use BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator;

class MediapartAuthenticator
    extends AbstractFormBasedAuthenticator
    implements WebsiteAuthenticator, WebsiteAuthenticator\UrlBased, WebsiteAuthenticator\CredentialsBased
{
    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var string */
    private $uri;

    public function setCredentials( $username, $password )
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function setUri( $uri )
    {
        $this->uri = $uri;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }
}
