<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\SiteReaderProxyBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class LmdSiteController
{
    public function uriAction( $uri )
    {
        return new Response( file_get_contents( 'http://www.monde-diplomatique.fr/' . $uri ) );
    }
}