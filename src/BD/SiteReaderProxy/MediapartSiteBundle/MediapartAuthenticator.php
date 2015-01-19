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
    implements WebsiteAuthenticator, WebsiteAuthenticator\UrlBased, WebsiteAuthenticator\CredentialsBased, WebsiteAuthenticator\FormBased
{
    public function getUsernameFieldName()
    {
        return 'name';
    }

    public function getPasswordFieldName()
    {
        return 'pass';
    }

    public function getExtraFormFields()
    {
        return ["op" => "ok", "form_id" => "user_login_block"];
    }
}
