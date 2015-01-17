<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\SiteReaderProxyBundle\DependencyInjection\Factory;

use Symfony\Component\Finder\Finder;

class YamlSitesRoutesFinderFactory
{
    public static function build()
    {
        $finder = new Finder();
        return $finder->files()->name( "*.yml" );
    }
}
