<?php
namespace BD\SiteReaderProxy\LmdSiteBundle;

use BD\SiteReaderProxyBundle\Proxy\AbstractFormBasedAuthenticator;
use BD\SiteReaderProxyBundle\Proxy\WebsiteAuthenticator;

class LmdAuthenticator
    extends AbstractFormBasedAuthenticator
    implements WebsiteAuthenticator, WebsiteAuthenticator\CredentialsBased, WebsiteAuthenticator\UrlBased
{
}
